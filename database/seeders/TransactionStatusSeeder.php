<?php

namespace Database\Seeders;

use App\Models\TransactionStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransactionStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TransactionStatus::insert([
            ['status' => 'On Payment'],
            ['status' => 'On Booking'],
            ['status' => 'Car Taken'],
            ['status' => 'Review By Admin'],
            ['status' => 'Review By User'],
            ['status' => 'Closed'],
            ['status' => 'Canceled'],
        ]);
    }
}
