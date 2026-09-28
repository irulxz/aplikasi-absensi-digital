<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PesertaDidik>
 */
class PesertaDidikFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'nis' => fake()->numerify('########'),
            'nama' => fake()->name(),
            'alamat' => fake()->address(),
        ];
    }
}