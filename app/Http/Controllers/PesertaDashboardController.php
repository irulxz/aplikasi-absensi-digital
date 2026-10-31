<?php

namespace App\Http\Controllers;

use App\Models\PesertaDidik;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PesertaDashboardController extends Controller
{
    public function index()
    {
        // Ambil data peserta yang sedang login
        $peserta = PesertaDidik::where(
            'user_id',
            Auth::id()
        )->first();

        // Statistik awal
        $statistik = [
            'Hadir' => 0,
            'Alpa'  => 0,
            'Izin'  => 0,
            'Sakit' => 0,
        ];

        $totalAbsensi = 0;
        $persentaseHadir = 0;

        // Hitung statistik jika peserta ditemukan
        if ($peserta) {
            $rekap = $peserta->absensis()
                ->select('status', DB::raw('COUNT(*) as total'))
                ->groupBy('status')
                ->pluck('total', 'status');

            foreach ($statistik as $status => $nilai) {
                $statistik[$status] = (int) ($rekap[$status] ?? 0);
            }

            $totalAbsensi = $peserta->absensis()->count();

            if ($totalAbsensi > 0) {
                $persentaseHadir = round(
                    ($statistik['Hadir'] / $totalAbsensi) * 100
                );
            }
        }

        return view('peserta.dashboard', compact(
            'peserta',
            'statistik',
            'totalAbsensi',
            'persentaseHadir'
        ));
    }
}
