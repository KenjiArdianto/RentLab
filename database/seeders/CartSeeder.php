<?php

namespace Database\Seeders;

use App\Models\Cart;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        Cart::insert([
            [
                'user_id' => 1,
                'vehicle_id' => 1,
                'start_date' => '2023-08-03',
                'end_date' => '2023-08-04',
                'subtotal' => 1000000.00,
            ],
            [
                'user_id' => 1,
                'vehicle_id' => 1,
                'start_date' => '2022-08-13',
                'end_date' => '2022-08-14',
                'subtotal' => 1000000.00,
            ],
            [
                'user_id' => 1,
                'vehicle_id' => 1,
                'start_date' => '2025-08-23',
                'end_date' => '2025-08-24',
                'subtotal' => 1000000.00,
            ],
            [
                'user_id' => 1,
                'vehicle_id' => 1,
                'start_date' => '2026-07-03',
                'end_date' => '2026-07-04',
                'subtotal' => 1000000.00,
            ],
            [
                'user_id' => 1,
                'vehicle_id' => 3,
                'start_date' => '2022-07-13',
                'end_date' => '2022-07-14',
                'subtotal' => 1000000.00,
            ],
            [
                'user_id' => 1,
                'vehicle_id' => 2,
                'start_date' => '2022-07-23',
                'end_date' => '2022-07-24',
                'subtotal' => 1000000.00,
            ],
            [
                'user_id' => 2,
                'vehicle_id' => 2,
                'start_date' => '2023-05-03',
                'end_date' => '2023-05-04',
                'subtotal' => 1000000.00,
            ],
            [
                'user_id' => 3,
                'vehicle_id' => 3,
                'start_date' => '2023-04-03',
                'end_date' => '2023-04-04',
                'subtotal' => 1000000.00,
            ],

            [
                'user_id' => 4,
                'vehicle_id' => 1,
                'start_date' => '2023-06-07',
                'end_date' => '2023-06-08',
                'subtotal' => 1000000.00,
            ],
            [
                'user_id' => 5,
                'vehicle_id' => 2,
                'start_date' => '2023-05-07',
                'end_date' => '2023-05-08',
                'subtotal' => 1000000.00,
            ],
            [
                'user_id' => 6,
                'vehicle_id' => 3,
                'start_date' => '2023-04-07',
                'end_date' => '2023-04-08',
                'subtotal' => 1000000.00,
            ]
        ]);
    }
}
