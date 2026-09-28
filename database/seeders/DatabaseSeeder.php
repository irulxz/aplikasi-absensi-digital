<?php

namespace Database\Seeders;

use App\Models\Absensi;
use App\Models\Jadwal;
use App\Models\MataPelajaran;
use App\Models\PesertaDidik;
use App\Models\Tutor;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Membuat user peserta didik
        $pesertaUsers = User::factory(30)->create([
            'role' => 'peserta_didik',
        ]);

        // Membuat user tutor
        $tutorUsers = User::factory(5)->create([
            'role' => 'tutor',
        ]);

        // Membuat data peserta didik
        foreach ($pesertaUsers as $user) {
            PesertaDidik::factory()->create([
                'user_id' => $user->id,
            ]);
        }

        // Membuat data tutor
        foreach ($tutorUsers as $user) {
            Tutor::factory()->create([
                'user_id' => $user->id,
            ]);
        }

        // Membuat data mata pelajaran
        MataPelajaran::factory(6)->create();

        // Membuat data jadwal
        Jadwal::factory(10)->create();

        // Membuat satu data absensi untuk setiap peserta didik
        $pesertas = PesertaDidik::all();
        $jadwals = Jadwal::all();

        foreach ($pesertas as $peserta) {
            Absensi::factory()->create([
                'peserta_didik_id' => $peserta->id,
                'jadwal_id' => $jadwals->random()->id,
            ]);
        }
    }
}