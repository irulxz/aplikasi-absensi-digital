<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AbsensiController extends Controller
{
    /**
     * Manajemen Absensi.
     */
    public function index(Request $request)
    {
        $query = Absensi::with([
            'pesertaDidik',
            'jadwal.mataPelajaran',
            'jadwal.tutor',
        ]);

        if ($request->filled('nama')) {
            $kataKunci = $request->nama;

            $query->whereHas('pesertaDidik', function ($q) use ($kataKunci) {
                $q->where('nama', 'like', "%{$kataKunci}%")
                  ->orWhere('nis', 'like', "%{$kataKunci}%");
            });
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $absensis = (clone $query)
            ->latest('tanggal')
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        $totalAbsensi = Absensi::count();

        $totalHadir = Absensi::whereRaw(
            'LOWER(status) = ?', ['hadir']
        )->count();

        $totalTerlambat = Absensi::whereRaw(
            'LOWER(status) = ?', ['terlambat']
        )->count();

        $totalTidakHadir = Absensi::whereIn(
            DB::raw('LOWER(status)'),
            ['alpa', 'izin', 'sakit', 'tidak hadir']
        )->count();

        return view('admin.absensi.index', compact(
            'absensis',
            'totalAbsensi',
            'totalHadir',
            'totalTerlambat',
            'totalTidakHadir'
        ));
    }

    /**
     * Rekap Absensi per peserta didik.
     */
    public function rekap(Request $request)
    {
        $request->validate([
            'nama' => ['nullable', 'string', 'max:255'],
            'tanggal_mulai' => ['nullable', 'date'],
            'tanggal_selesai' => [
                'nullable',
                'date',
                'after_or_equal:tanggal_mulai',
            ],
            'status' => [
                'nullable',
                'in:Hadir,Terlambat,Izin,Sakit,Alpa,Tidak Hadir',
            ],
        ]);

        $query = Absensi::query();

        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_selesai);
        }

        if ($request->filled('nama')) {
            $kataKunci = $request->nama;

            $query->whereHas('pesertaDidik', function ($q) use ($kataKunci) {
                $q->where('nama', 'like', "%{$kataKunci}%")
                  ->orWhere('nis', 'like', "%{$kataKunci}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $rekapQuery = (clone $query)
            ->select(
                'peserta_didik_id',
                DB::raw('COUNT(*) as total_absensi'),
                DB::raw("SUM(CASE WHEN LOWER(status) = 'hadir' THEN 1 ELSE 0 END) as total_hadir"),
                DB::raw("SUM(CASE WHEN LOWER(status) = 'terlambat' THEN 1 ELSE 0 END) as total_terlambat"),
                DB::raw("SUM(CASE WHEN LOWER(status) = 'izin' THEN 1 ELSE 0 END) as total_izin"),
                DB::raw("SUM(CASE WHEN LOWER(status) = 'sakit' THEN 1 ELSE 0 END) as total_sakit"),
                DB::raw("SUM(CASE WHEN LOWER(status) IN ('alpa', 'tidak hadir') THEN 1 ELSE 0 END) as total_alpa")
            )
            ->whereNotNull('peserta_didik_id')
            ->groupBy('peserta_didik_id');

        $rekap = DB::query()
            ->fromSub($rekapQuery, 'rekap_absensi')
            ->join(
                'peserta_didik',
                'peserta_didik.id',
                '=',
                'rekap_absensi.peserta_didik_id'
            )
            ->select(
                'rekap_absensi.*',
                'peserta_didik.nama',
                'peserta_didik.nis'
            )
            ->orderBy('peserta_didik.nama')
            ->paginate(15)
            ->withQueryString();

        // Ringkasan berdasarkan periode dan nama/NIS.
        $ringkasanQuery = Absensi::query();

        if ($request->filled('tanggal_mulai')) {
            $ringkasanQuery->whereDate('tanggal', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_selesai')) {
            $ringkasanQuery->whereDate('tanggal', '<=', $request->tanggal_selesai);
        }

        if ($request->filled('nama')) {
            $kataKunci = $request->nama;

            $ringkasanQuery->whereHas('pesertaDidik', function ($q) use ($kataKunci) {
                $q->where('nama', 'like', "%{$kataKunci}%")
                  ->orWhere('nis', 'like', "%{$kataKunci}%");
            });
        }

        $totalAbsensi = (clone $ringkasanQuery)->count();

        $totalHadir = (clone $ringkasanQuery)
            ->whereRaw('LOWER(status) = ?', ['hadir'])->count();

        $totalTerlambat = (clone $ringkasanQuery)
            ->whereRaw('LOWER(status) = ?', ['terlambat'])->count();

        $totalIzin = (clone $ringkasanQuery)
            ->whereRaw('LOWER(status) = ?', ['izin'])->count();

        $totalSakit = (clone $ringkasanQuery)
            ->whereRaw('LOWER(status) = ?', ['sakit'])->count();

        $totalAlpa = (clone $ringkasanQuery)
            ->whereIn(DB::raw('LOWER(status)'), ['alpa', 'tidak hadir'])
            ->count();

        $totalTidakHadir = $totalIzin + $totalSakit + $totalAlpa;

        return view('admin.rekap-absensi.index', compact(
            'rekap',
            'totalAbsensi',
            'totalHadir',
            'totalTerlambat',
            'totalIzin',
            'totalSakit',
            'totalAlpa',
            'totalTidakHadir'
        ));
    }

    /**
     * Ekspor rekap absensi ke CSV yang dapat dibuka dengan Excel.
     */
    public function export(Request $request)
    {
        $request->validate([
            'nama' => ['nullable', 'string', 'max:255'],
            'tanggal_mulai' => ['nullable', 'date'],
            'tanggal_selesai' => [
                'nullable',
                'date',
                'after_or_equal:tanggal_mulai',
            ],
        ]);

        $query = Absensi::query();

        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_selesai);
        }

        if ($request->filled('nama')) {
            $kataKunci = $request->nama;

            $query->whereHas('pesertaDidik', function ($q) use ($kataKunci) {
                $q->where('nama', 'like', "%{$kataKunci}%")
                  ->orWhere('nis', 'like', "%{$kataKunci}%");
            });
        }

        $rekapQuery = (clone $query)
            ->select(
                'peserta_didik_id',
                DB::raw('COUNT(*) as total_absensi'),
                DB::raw("SUM(CASE WHEN LOWER(status) = 'hadir' THEN 1 ELSE 0 END) as total_hadir"),
                DB::raw("SUM(CASE WHEN LOWER(status) = 'terlambat' THEN 1 ELSE 0 END) as total_terlambat"),
                DB::raw("SUM(CASE WHEN LOWER(status) = 'izin' THEN 1 ELSE 0 END) as total_izin"),
                DB::raw("SUM(CASE WHEN LOWER(status) = 'sakit' THEN 1 ELSE 0 END) as total_sakit"),
                DB::raw("SUM(CASE WHEN LOWER(status) IN ('alpa', 'tidak hadir') THEN 1 ELSE 0 END) as total_alpa")
            )
            ->whereNotNull('peserta_didik_id')
            ->groupBy('peserta_didik_id');

        $data = DB::query()
            ->fromSub($rekapQuery, 'rekap_absensi')
            ->join(
                'peserta_didik',
                'peserta_didik.id',
                '=',
                'rekap_absensi.peserta_didik_id'
            )
            ->select(
                'peserta_didik.nis',
                'peserta_didik.nama',
                'rekap_absensi.total_hadir',
                'rekap_absensi.total_terlambat',
                'rekap_absensi.total_izin',
                'rekap_absensi.total_sakit',
                'rekap_absensi.total_alpa',
                'rekap_absensi.total_absensi'
            )
            ->orderBy('peserta_didik.nama')
            ->get();

        $namaFile = 'rekap-absensi-' . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($data) {
            $handle = fopen('php://output', 'w');

            // BOM agar teks Indonesia terbaca dengan baik di Excel.
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                'NIS',
                'Nama Peserta Didik',
                'Hadir',
                'Terlambat',
                'Izin',
                'Sakit',
                'Alpa',
                'Total Absensi',
                'Persentase Kehadiran (%)',
            ], ';');

            foreach ($data as $item) {
                $persentase = $item->total_absensi > 0
                    ? round(
                        ($item->total_hadir / $item->total_absensi) * 100,
                        2
                    )
                    : 0;

                fputcsv($handle, [
                    $item->nis,
                    $item->nama,
                    $item->total_hadir,
                    $item->total_terlambat,
                    $item->total_izin,
                    $item->total_sakit,
                    $item->total_alpa,
                    $item->total_absensi,
                    $persentase,
                ], ';');
            }

            fclose($handle);
        }, $namaFile, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
