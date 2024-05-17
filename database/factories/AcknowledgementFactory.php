<?php

namespace Database\Factories;

use App\Models\EthocaAcknowledgement;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class AcknowledgementFactory extends Factory
{
    protected $model = EthocaAcknowledgement::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'status' => fake()->randomElement(['success', 'failed', 'retry']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
