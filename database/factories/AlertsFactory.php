<?php

namespace Database\Factories;

use App\Models\EthocaAlert;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class AlertsFactory extends Factory
{
    protected $model = EthocaAlert::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        
        return [
            'ethoca_response_id' => fake()->numberBetween(1, 10),
            'crm_transaction_id' => fake()->numberBetween(10000, 99999),
            'is_handled' => fake()->boolean(),
            'is_updated' => fake()->boolean(),
            'is_ack' => fake()->boolean(),
            'ethoca_id' => fake()->regexify('[A-Za-z0-9]{25}'),
            'type' => fake()->randomElement([' issuer_alert', 'customerdispute_alert']),
            'alert_timestamp' => fake()->dateTime(),
            'age' => fake()->numberBetween(1, 50),
            'issuer' => fake()->company(),
            'card_number' => fake()->creditCardNumber(),
            'card_bin' => fake()->regexify('[0-9]{6}'),
            'card_last4' => substr(fake()->creditCardNumber(), -4),
            'arn' => fake()->regexify('[0-9]{23,24}'),
            'transaction_timestamp' => fake()->dateTime(),
            'merchant_descriptor' => fake()->company(),
            'member_id' => fake()->randomNumber(),
            'mcc' => fake()->regexify('[0-9]{4}'),
            'amount' => fake()->randomFloat(2, 1, 10000),
            'currency' => fake()->currencyCode(),
            'transaction_type' => fake()->randomElement([' keyed', 'swiped', 'eCommerce', 'unknown']),
            'initiated_by' => fake()->randomElement(['cardholder', 'issuer', 'not_available']),
            'is_3d_secure' => fake()->randomElement(['yes', 'no', 'not_available']),
            'source' => fake()->randomElement(['issuer', 'other']),
            'auth_code' => fake()->regexify('[A-Z0-9]{8}'),
            'merchant_member_name' => fake()->company(),
            'ethoca_transaction_id' => fake()->unique()->regexify('[A-Za-z0-9]{65}'),
            'chargeback_reason_code' => fake()->regexify('[A-Za-z0-9]{5,30}'),
            'chargeback_amount' => fake()->randomFloat(2, 1, 10000),
            'chargeback_currency' => fake()->currencyCode(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
