<?php

namespace App\Http\Controllers;

use App\Models\PesertaDidik;
use Illuminate\Support\Facades\Auth;

class PesertaRiwayatAbsensiController extends Controller
{
    public function index()
    {
        $peserta = PesertaDidik::where('user_id', Auth::id())
            ->firstOrFail();

        $absensis = $peserta->absensis()
            ->with(['jadwal.mataPelajaran', 'jadwal.tutor'])
            ->orderByDesc('tanggal')
            ->orderByDesc('id')
            ->paginate(10);

        return view('peserta.riwayat-absensi', compact(
            'peserta',
            'absensis'
        ));
    }
}
