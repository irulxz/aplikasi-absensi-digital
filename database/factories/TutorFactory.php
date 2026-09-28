<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tutor>
 */
class TutorFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'nip' => fake()->numerify('################'),
            'nama' => fake()->name(),
        ];
    }
}