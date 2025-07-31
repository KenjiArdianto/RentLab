<?php

namespace Database\Seeders;

use App\Models\Payment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Payment::insert([
            [
                'external_id' => 'PAYMENT-SEED-1',
                'amount' => 100000,
                'status' => 'completed',
                'paid_at' => now(),
                'payment_method' => 'credit_card',
                'payment_channel' => 'online',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'external_id' => 'PAYMENT-SEED-2',
                'amount' => 200000,
                'status' => 'pending',
                'paid_at' => null,
                'payment_method' => 'bank_transfer',
                'payment_channel' => 'offline',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
