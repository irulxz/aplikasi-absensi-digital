<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\PesertaDidik;
use Illuminate\Support\Facades\Auth;

class PesertaJadwalController extends Controller
{
    public function index()
    {
        $peserta = PesertaDidik::where('user_id', Auth::id())
            ->firstOrFail();

        $jadwals = Jadwal::with(['mataPelajaran', 'tutor'])
            ->where('program_paket', $peserta->program_paket)
            ->orderByRaw("
                FIELD(
                    hari,
                    'Senin',
                    'Selasa',
                    'Rabu',
                    'Kamis',
                    'Jumat',
                    'Sabtu',
                    'Minggu'
                )
            ")
            ->orderBy('jam_mulai')
            ->get();

        return view('peserta.jadwal', compact(
            'peserta',
            'jadwals'
        ));
    }
}
