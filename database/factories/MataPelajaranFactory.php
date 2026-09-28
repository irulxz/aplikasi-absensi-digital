<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MataPelajaran>
 */
class MataPelajaranFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nama' => fake()->randomElement([
                'Bahasa Indonesia',
                'Matematika',
                'Bahasa Inggris',
                'IPA',
                'IPS',
                'PPKn',
            ]),
            'tingkat' => fake()->randomElement([
                'Dasar',
                'Menengah',
                'Lanjutan',
            ]),
        ];
    }
}