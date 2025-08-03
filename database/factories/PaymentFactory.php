<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str; // <-- Pastikan ini ada

class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'external_id' => 'RENTAL-' . Str::random(12),
            'amount' => $this->faker->numberBetween(100000, 500000),
            'status' => $this->faker->randomElement(['PAID', 'PENDING', 'FAILED', 'EXPIRED']),
            'payment_method' => $this->faker->randomElement(['Credit Card', 'Bank Transfer', 'E-Wallet', 'QRIS']),
        ];
    }
}