<?php

namespace Database\Factories;

use App\Models\Jadwal;
use App\Models\PesertaDidik;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Absensi>
 */
class AbsensiFactory extends Factory
{
    public function definition(): array
    {
        return [
            'peserta_didik_id' => PesertaDidik::query()->inRandomOrder()->value('id'),
            'jadwal_id' => Jadwal::query()->inRandomOrder()->value('id'),
            'tanggal' => fake()->date(),
            'status' => fake()->randomElement([
                'Hadir',
                'Izin',
                'Sakit',
                'Alpa',
            ]),
        ];
    }
}