<?php

namespace Database\Factories;

use App\Models\MataPelajaran;
use App\Models\Tutor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Jadwal>
 */
class JadwalFactory extends Factory
{
    public function definition(): array
    {
        return [
            'mata_pelajaran_id' => MataPelajaran::query()->inRandomOrder()->value('id'),
            'tutor_id' => Tutor::query()->inRandomOrder()->value('id'),
            'hari' => fake()->randomElement([
                'Senin',
                'Selasa',
                'Rabu',
                'Kamis',
                'Jumat',
                'Sabtu',
            ]),
            'jam_mulai' => fake()->randomElement([
                '08:00:00',
                '09:00:00',
                '10:00:00',
                '13:00:00',
                '14:00:00',
            ]),
            'jam_selesai' => fake()->randomElement([
                '09:00:00',
                '10:00:00',
                '11:00:00',
                '14:00:00',
                '15:00:00',
            ]),
        ];
    }
}