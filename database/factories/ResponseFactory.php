<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ResponseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            'major_code' => fake()->randomElement([0, 2, 3, 4]),
            'status' => fake()->randomElement(['Continue', 'Success', 'Fail']),
            'NumberOfAlerts' => null
        ];
    }
}
