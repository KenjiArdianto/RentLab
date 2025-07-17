<?php

namespace Database\Seeders;

use App\Models\Transaction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        Transaction::insert([
            [
                'vehicle_id' => 1,
                'user_id' => 1,
                'driver_id' => 1,
                'start_book_date' => '2025-07-26',
                'end_book_date' => '2025-07-27',
                'return_date' => '2025-07-28',
                'status' => 0
            ],
            [
                'vehicle_id' => 2,
                'user_id' => 2,
                'driver_id' => 2,
                'start_book_date' => '2025-07-26',
                'end_book_date' => '2025-07-27',
                'return_date' => '2025-07-28',
                'status' => 1
            ],
            [
                'vehicle_id' => 3,
                'user_id' => 3,
                'driver_id' => 3,
                'start_book_date' => '2025-07-26',
                'end_book_date' => '2025-07-27',
                'return_date' => '2025-07-28',
                'status' => 2
            ],
            [
                'vehicle_id' => 4,
                'user_id' => 4,
                'driver_id' => 4,
                'start_book_date' => '2025-07-26',
                'end_book_date' => '2025-07-27',
                'return_date' => '2025-07-28',
                'status' => 3
            ],
            [
                'vehicle_id' => 5,
                'user_id' => 5,
                'driver_id' => 5,
                'start_book_date' => '2025-07-26',
                'end_book_date' => '2025-07-27',
                'return_date' => '2025-07-28',
                'status' => 4
            ],
            [
                'vehicle_id' => 6,
                'user_id' => 6,
                'driver_id' => 6,
                'start_book_date' => '2025-07-26',
                'end_book_date' => '2025-07-27',
                'return_date' => '2025-07-28',
                'status' => 5
            ],
            [
                'vehicle_id' => 7,
                'user_id' => 7,
                'driver_id' => 7,
                'start_book_date' => '2025-07-26',
                'end_book_date' => '2025-07-27',
                'return_date' => '2025-07-28',
                'status' => 6
            ]
        ]);
    }
}
