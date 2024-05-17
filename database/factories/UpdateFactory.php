<?php

namespace Database\Factories;

use App\Models\EthocaUpdate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class UpdateFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = EthocaUpdate::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $confirmed_fraud_statuses = [
            "stopped",
            "partially_stopped",
            "previously_cancelled",
            "missed",
            "notfound",
            "account_suspended",
            "in_progress",
            "shipper_contacted",
            "other"
        ];
        $customer_dispute_statuses = [
            "resolved",
            "previously_refunded",
            "unresolved_dispute",
            "notfound",
            "other"

        ];

        $status = $this->faker->randomElement(array_merge($confirmed_fraud_statuses, $customer_dispute_statuses));
        return [
            'outcome' => $this->faker->sentence(), // 'Ethoca 360 Alerts Request'
            'status' => $status,
            'refunded' => $this->faker->randomElement(["refunded", "not refunded", "not settled"]),
            'created_at' => now(),
            'updated_at' => now(),

        ];
    }
}
