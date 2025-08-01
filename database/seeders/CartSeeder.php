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
                'end_date' => '2023-06-04',
            ],
            [
                'user_id' => 2,
                'vehicle_id' => 2,
                'start_date' => '2023-05-03',
                'end_date' => '2023-05-04',
            ],
            [
                'user_id' => 3,
                'vehicle_id' => 3,
                'start_date' => '2023-04-03',
                'end_date' => '2023-04-04',
            ]
        ]);
    }
}
