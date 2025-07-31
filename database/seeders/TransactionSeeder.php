<?php

namespace Database\Seeders;

use App\Models\Payment;
use App\Models\Transaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Nonaktifkan pengecekan foreign key untuk menghapus data dengan aman
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Transaction::truncate();
        Payment::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Data seeder Anda, dengan sedikit perbaikan (menghapus external_id duplikat dan menambah harga)
        $transactionsData = [
            ['external_id' => 'RENTAL-001', 'vehicle_id' => 1, 'user_id' => 1, 'driver_id' => 1, 'start_book_date' => '2025-08-10', 'end_book_date' => '2025-08-11', 'return_date' => '2025-08-12', 'transaction_status_id' => 2, 'price' => 250000],
            ['external_id' => 'RENTAL-002', 'vehicle_id' => 3, 'user_id' => 1, 'driver_id' => null, 'start_book_date' => '2023-05-03', 'end_book_date' => '2023-05-07', 'return_date' => '2023-05-07', 'transaction_status_id' => 5, 'price' => 1200000],
            ['external_id' => 'RENTAL-003', 'vehicle_id' => 5, 'user_id' => 1, 'driver_id' => 2, 'start_book_date' => '2025-08-10', 'end_book_date' => '2025-08-11', 'return_date' => '2025-08-12', 'transaction_status_id' => 1, 'price' => 300000],
            ['external_id' => 'RENTAL-004', 'vehicle_id' => 2, 'user_id' => 1, 'driver_id' => 4, 'start_book_date' => '2025-08-10', 'end_book_date' => '2025-08-11', 'return_date' => '2025-08-12', 'transaction_status_id' => 3, 'price' => 650000],
            ['external_id' => 'RENTAL-005', 'vehicle_id' => 7, 'user_id' => 1, 'driver_id' => null, 'start_book_date' => '2025-08-10', 'end_book_date' => '2025-08-15', 'return_date' => '2025-08-15', 'transaction_status_id' => 5, 'price' => 1750000],
            ['external_id' => 'RENTAL-006', 'vehicle_id' => 1, 'user_id' => 1, 'driver_id' => 3, 'start_book_date' => '2025-04-07', 'end_book_date' => '2025-04-14', 'return_date' => '2025-04-14', 'transaction_status_id' => 5, 'price' => 800000],
            ['external_id' => 'RENTAL-007', 'vehicle_id' => 10, 'user_id' => 1, 'driver_id' => null, 'start_book_date' => '2025-07-01', 'end_book_date' => '2025-07-02', 'return_date' => '2025-07-02', 'transaction_status_id' => 5, 'price' => 400000],
            ['external_id' => 'RENTAL-008', 'vehicle_id' => 13, 'user_id' => 1, 'driver_id' => 1, 'start_book_date' => '2025-07-06', 'end_book_date' => '2025-07-08', 'return_date' => '2025-07-08', 'transaction_status_id' => 5, 'price' => 550000],
            ['external_id' => 'RENTAL-009', 'vehicle_id' => 6, 'user_id' => 1, 'driver_id' => null, 'start_book_date' => '2025-01-20', 'end_book_date' => '2025-01-22', 'return_date' => '2025-01-22', 'transaction_status_id' => 7, 'price' => 600000],
        ];

        foreach ($transactionsData as $data) {
            // Tentukan status pembayaran berdasarkan status transaksi
            $paymentStatus = match((int)$data['transaction_status_id']) {
                2, 3, 4, 5, 6 => 'PAID', // Status Lunas, Selesai, dll.
                7 => 'EXPIRED',
                default => 'PENDING', // Status 1 (Pending)
            };

            // 1. Buat record di tabel 'payments'
            $payment = Payment::create([
                // 'user_id' => $data['user_id'],
                'external_id' => $data['external_id'],
                'amount' => $data['price'],
                'status' => $paymentStatus,
                'paid_at' => $paymentStatus === 'PAID' ? now() : null,
            ]);

            // 2. Buat record di tabel 'transactions'
            Transaction::create([
                'payment_id' => $payment->id, // <-- Kunci utamanya di sini
                'vehicle_id' => $data['vehicle_id'],
                'user_id' => $data['user_id'],
                'driver_id' => $data['driver_id'],
                'transaction_status_id' => $data['transaction_status_id'],
                'start_book_date' => $data['start_book_date'],
                'end_book_date' => $data['end_book_date'],
                'return_date' => $data['return_date'],
                'price' => $data['price'],
            ]);
        }
    }
}
