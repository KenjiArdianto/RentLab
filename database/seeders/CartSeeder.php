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
                'start_date' => '2023-06-03',
                'end_date' => '2023-06-06',
            ],
            [
                'user_id' => 1,
                'vehicle_id' => 2,
                'start_date' => '2023-05-03',
                'end_date' => '2023-05-06',
            ],
            [
                'user_id' => 1,
                'vehicle_id' => 3,
                'start_date' => '2023-04-03',
                'end_date' => '2023-04-06',
            ]
        ]);
    }
}
