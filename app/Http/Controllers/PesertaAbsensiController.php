<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Jadwal;
use App\Models\PesertaDidik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class PesertaAbsensiController extends Controller
{
    private function hariIndonesia(): string
    {
        return [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
        ][now()->format('l')];
    }

    public function index()
    {
        $peserta = PesertaDidik::where('user_id', Auth::id())
            ->firstOrFail();

        $jadwals = Jadwal::with(['mataPelajaran', 'tutor'])
            ->where('program_paket', $peserta->program_paket)
            ->where('hari', $this->hariIndonesia())
            ->orderBy('jam_mulai')
            ->get();

        $absensiHariIni = $peserta->absensis()
            ->whereDate('tanggal', today())
            ->get()
            ->keyBy('jadwal_id');

        $sekarang = now()->format('H:i:s');

        return view('peserta.absensi', compact(
            'peserta',
            'jadwals',
            'absensiHariIni',
            'sekarang'
        ));
    }

    public function store(Request $request, $jadwalId)
    {
        $peserta = PesertaDidik::where('user_id', Auth::id())
            ->firstOrFail();

        $jadwal = Jadwal::whereKey($jadwalId)
            ->where('program_paket', $peserta->program_paket)
            ->firstOrFail();

        // Validasi hari berdasarkan waktu server.
        abort_unless(
            $jadwal->hari === $this->hariIndonesia(),
            403,
            'Absensi hanya dapat dilakukan pada hari jadwal belajar.'
        );

        // Validasi jam belajar berdasarkan waktu server.
        $waktuSekarang = now()->format('H:i:s');
        $jamMulai = substr((string) $jadwal->jam_mulai, 0, 8);
        $jamSelesai = substr((string) $jadwal->jam_selesai, 0, 8);

        abort_unless(
            $waktuSekarang >= $jamMulai &&
            $waktuSekarang <= $jamSelesai,
            403,
            'Absensi hanya dapat dilakukan selama jam belajar.'
        );

        // Jangan menimpa status yang sudah dicatat oleh Tutor/Admin.
        $sudahAbsen = $peserta->absensis()
            ->where('jadwal_id', $jadwal->id)
            ->whereDate('tanggal', today())
            ->exists();

        if ($sudahAbsen) {
            return back()->with(
                'error',
                'Absensi untuk jadwal ini sudah tercatat hari ini.'
            );
        }

        try {
            DB::transaction(function () use ($peserta, $jadwal) {
                // Mengurangi risiko permintaan bersamaan untuk peserta yang sama.
                PesertaDidik::whereKey($peserta->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                // Periksa ulang di dalam transaksi.
                $sudahAbsen = Absensi::where(
                    'peserta_didik_id',
                    $peserta->id
                )
                    ->where('jadwal_id', $jadwal->id)
                    ->whereDate('tanggal', today())
                    ->exists();

                if ($sudahAbsen) {
                    throw new \RuntimeException(
                        'ABSENSI_SUDAH_TERCATAT'
                    );
                }

                Absensi::create([
                    'peserta_didik_id' => $peserta->id,
                    'jadwal_id' => $jadwal->id,
                    'tanggal' => today(),
                    'status' => 'Hadir',
                ]);
            });
        } catch (\RuntimeException $e) {
            if ($e->getMessage() === 'ABSENSI_SUDAH_TERCATAT') {
                return back()->with(
                    'error',
                    'Absensi untuk jadwal ini sudah tercatat hari ini.'
                );
            }

            throw $e;
        } catch (QueryException $e) {
            // MySQL error 1062: unique index dilanggar.
            if (($e->errorInfo[1] ?? null) == 1062) {
                return back()->with(
                    'error',
                    'Absensi untuk jadwal ini sudah tercatat hari ini.'
                );
            }

            // Jangan menyembunyikan error database yang lain.
            throw $e;
        }

        return back()->with(
            'success',
            'Absensi hadir berhasil dicatat!'
        );
    }
}
