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
                'start_book_date' => '2023-06-03',
                'end_book_date' => '2023-06-04',
                'return_date' => '2023-06-05',
                'transaction_status_id' => 1
            ],
            [
                'vehicle_id' => 2,
                'user_id' => 2,
                'driver_id' => 2,
                'start_book_date' => '2023-05-03',
                'end_book_date' => '2023-05-04',
                'return_date' => '2023-05-05',
                'transaction_status_id' => 2
            ],
            [
                'vehicle_id' => 3,
                'user_id' => 3,
                'driver_id' => 3,
                'start_book_date' => '2023-04-03',
                'end_book_date' => '2023-04-04',
                'return_date' => '2023-04-05',
                'transaction_status_id' => 3
            ],
            [
                'vehicle_id' => 4,
                'user_id' => 4,
                'driver_id' => 4,
                'start_book_date' => '2023-03-03',
                'end_book_date' => '2023-03-04',
                'return_date' => '2023-03-05',
                'transaction_status_id' => 4
            ],
            [
                'vehicle_id' => 5,
                'user_id' => 5,
                'driver_id' => 5,
                'start_book_date' => '2023-02-03',
                'end_book_date' => '2023-02-04',
                'return_date' => '2023-02-05',
                'transaction_status_id' => 5
            ],
            [
                'vehicle_id' => 6,
                'user_id' => 6,
                'driver_id' => 6,
                'start_book_date' => '2023-01-03',
                'end_book_date' => '2023-01-04',
                'return_date' => '2023-01-05',
                'transaction_status_id' => 6
            ],
            [
                'vehicle_id' => 7,
                'user_id' => 7,
                'driver_id' => 7,
                'start_book_date' => '2022-12-03',
                'end_book_date' => '2022-12-04',
                'return_date' => '2022-12-05',
                'transaction_status_id' => 7
            ]
        ]);
    }
}
