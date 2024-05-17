<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\EthocaAlert;
use App\Models\EthocaError;
use App\Models\EthocaRequest;
use App\Models\EthocaResponse;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        // recived alerts with no errors
        EthocaRequest::factory(10)
            ->has(EthocaResponse::factory([
                'major_code' => 1,
                'status' => 'success',
                'number_of_alerts' => 10,
            ])->count(1)
                ->has(EthocaAlert::factory(10)))
            ->create();

        // recived alerts with errors
        for ($i = 0; $i < 10; $i++) {
            # code...
            EthocaError::factory([
                'model' => 'App\Models\EthocaResponse',
            ])->for(EthocaResponse::factory([
                'major_code' => fake()->randomElement([2, 3, 4]),
                'status' => 'Fail',
                'number_of_alerts' => 0,
            ])->for(EthocaRequest::factory()))->create();
        }
    }
}
