<?php

namespace Database\Factories;

use App\Models\EthocaRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class RequestFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = EthocaRequest::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $code = fake()->numberBetween(1, 3);
        $title = 'Ethoca 360 Alerts Request';
        switch ($code) {
            case 1:
                $title = 'Ethoca 360 Alerts Request';
                $alert_type = 'all';
                break;
            case 2:
                $title = 'Acknowledge Ethoca Alerts Request';
                $alert_type = null;

                break;
            case 3:
                $title = 'Update Ethoca Alerts Request';
                $alert_type = null;

                break;
        }
        return [
            'title' => $title,
            'ethoca_fun_code' => $code,
            'alert_type' => $alert_type
        ];
    }
}
