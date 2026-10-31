<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Jadwal;
use App\Models\PesertaDidik;
use App\Models\Tutor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TutorAbsensiController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $tutor = Tutor::where('user_id', $user->id)->first();

        $jadwals = collect();

        if ($tutor) {
            $jadwals = $tutor->jadwals()
                ->with('mataPelajaran')
                ->orderByRaw(
                    "FIELD(hari, 'Senin', 'Selasa', 'Rabu',
                    'Kamis', 'Jumat', 'Sabtu', 'Minggu')"
                )
                ->orderBy('jam_mulai')
                ->get();
        }

        foreach ($jadwals as $jadwal) {
            $jadwal->absensi_aktif =
                $this->jadwalSedangBerlangsung($jadwal);
        }

        return view('tutor.absensi.index', compact(
            'user',
            'tutor',
            'jadwals'
        ));
    }

    public function rekap()
    {
        $tutor = Tutor::where('user_id', Auth::id())->first();

        $kelompokRekap = collect();

        if ($tutor) {
            $jadwals = $tutor->jadwals()
                ->with([
                    'mataPelajaran',
                    'absensis' => function ($query) {
                        $query->with('pesertaDidik')
                            ->orderByDesc('tanggal')
                            ->orderByDesc('id');
                    },
                ])
                ->get();

            $kelompokRekap = $jadwals
                ->groupBy(function ($jadwal) {
                    return $jadwal->mata_pelajaran_id
                        . '|'
                        . $jadwal->program_paket;
                })
                ->map(function ($jadwals) {
                    $jadwalPertama = $jadwals->first();

                    $absensis = $jadwals
                        ->flatMap(function ($jadwal) {
                            return $jadwal->absensis;
                        })
                        ->sortByDesc('tanggal')
                        ->values();

                    return [
                        'mata_pelajaran' => $jadwalPertama
                            ->mataPelajaran->nama ?? '-',
                        'program_paket' => $jadwalPertama->program_paket,
                        'absensis' => $absensis,
                    ];
                })
                ->values();
        }

        return view('tutor.rekap.index', compact(
            'tutor',
            'kelompokRekap'
        ));
    }

    public function create(Jadwal $jadwal)
    {
        $tutor = Tutor::where('user_id', Auth::id())->first();

        abort_unless(
            $tutor && (int) $jadwal->tutor_id === (int) $tutor->id,
            403,
            'Anda tidak memiliki akses ke jadwal ini.'
        );

        abort_unless(
            $this->jadwalSedangBerlangsung($jadwal),
            403,
            'Input absensi hanya tersedia selama jam pelajaran berlangsung.'
        );

        $pesertaDidiks = PesertaDidik::where(
            'program_paket',
            $jadwal->program_paket
        )->orderBy('nama')->get();

        $absensis = Absensi::where('jadwal_id', $jadwal->id)
            ->whereDate('tanggal', Carbon::now('Asia/Jakarta')->toDateString())
            ->get()
            ->keyBy('peserta_didik_id');

        $jadwal->load('mataPelajaran');

        return view('tutor.absensi.create', compact(
            'jadwal',
            'pesertaDidiks',
            'absensis'
        ));
    }

    public function store(Request $request, Jadwal $jadwal)
    {
        $tutor = Tutor::where('user_id', Auth::id())->first();

        abort_unless(
            $tutor && (int) $jadwal->tutor_id === (int) $tutor->id,
            403,
            'Anda tidak memiliki akses ke jadwal ini.'
        );

        abort_unless(
            $this->jadwalSedangBerlangsung($jadwal),
            403,
            'Input absensi hanya tersedia selama jam pelajaran berlangsung.'
        );

        $validated = $request->validate([
            'absensi' => ['required', 'array', 'min:1'],
            'absensi.*' => ['required', 'in:Hadir,Izin,Sakit,Alpa'],
        ], [
            'absensi.required' => 'Silakan isi absensi peserta didik.',
            'absensi.*.in' => 'Status absensi yang dipilih tidak valid.',
        ]);

        $pesertaIds = array_map(
            'intval',
            array_keys($validated['absensi'])
        );

        $pesertaValid = PesertaDidik::whereIn('id', $pesertaIds)
            ->where('program_paket', $jadwal->program_paket)
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();

        abort_if(
            count($pesertaValid) !== count(array_unique($pesertaIds)),
            422,
            'Terdapat peserta didik yang tidak sesuai dengan paket jadwal.'
        );

        $tanggal = Carbon::now('Asia/Jakarta')->toDateString();

        DB::transaction(function () use (
            $validated,
            $pesertaValid,
            $jadwal,
            $tanggal
        ) {
            foreach ($pesertaValid as $pesertaId) {
                Absensi::updateOrCreate(
                    [
                        'peserta_didik_id' => $pesertaId,
                        'jadwal_id' => $jadwal->id,
                        'tanggal' => $tanggal,
                    ],
                    [
                        'status' => $validated['absensi'][$pesertaId],
                    ]
                );
            }
        });

        return redirect()
            ->route('tutor.absensi.index')
            ->with('success', 'Absensi peserta didik berhasil disimpan.');
    }

    private function jadwalSedangBerlangsung($jadwal): bool
    {
        $sekarang = Carbon::now('Asia/Jakarta');

        $hariIni = [
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
            7 => 'Minggu',
        ];

        if (($hariIni[$sekarang->dayOfWeekIso] ?? '') !== $jadwal->hari) {
            return false;
        }

        $mulai = Carbon::parse(
            $sekarang->toDateString() . ' ' . $jadwal->jam_mulai,
            'Asia/Jakarta'
        );

        $selesai = Carbon::parse(
            $sekarang->toDateString() . ' ' . $jadwal->jam_selesai,
            'Asia/Jakarta'
        );

        return $sekarang->greaterThanOrEqualTo($mulai)
            && $sekarang->lessThan($selesai);
    }
}
