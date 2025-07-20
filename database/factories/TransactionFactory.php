<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    
    public function definition(): array
    {

        $startDate = $this->faker->dateTimeBetween('-1 month', 'now');
        $endDate   = (clone $startDate)->modify('+' . rand(1, 5) . ' days');
        $returnDate = rand(0, 1) ? (clone $endDate)->modify('+' . rand(0, 2) . ' days') : null;

        return [
            'vehicle_id'       => $this->faker->numberBetween(1, 20),  // Random vehicle ID
            'user_id'          => $this->faker->numberBetween(1, 50),  // Random user ID
            'driver_id'        => $this->faker->numberBetween(1, 10),  // Random driver ID
            'start_book_date'  => $startDate->format('Y-m-d'),
            'end_book_date'    => $endDate->format('Y-m-d'),
            'return_date'      => $returnDate ? $returnDate->format('Y-m-d') : null,
            'status'           => $this->faker->numberBetween(1, 6),    // Random status ID (1–7)
        ];
    }
}
