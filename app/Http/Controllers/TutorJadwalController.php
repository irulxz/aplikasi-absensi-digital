<?php

namespace App\Http\Controllers;

use App\Models\Tutor;
use Illuminate\Support\Facades\Auth;

class TutorJadwalController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $tutor = Tutor::where('user_id', $user->id)
            ->first();

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

        return view('tutor.jadwal.index', compact(
            'user',
            'tutor',
            'jadwals'
        ));
    }
}
