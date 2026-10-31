<?php

namespace App\Http\Controllers;

use App\Models\Tutor;
use Illuminate\Support\Facades\Auth;

class TutorDashboardController extends Controller
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
                ->orderBy('hari')
                ->orderBy('jam_mulai')
                ->get();
        }

        return view('tutor.dashboard', compact(
            'user',
            'tutor',
            'jadwals'
        ));
    }
}
