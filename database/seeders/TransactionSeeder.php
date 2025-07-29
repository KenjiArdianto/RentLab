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
        Transaction::insert([
            [
                'external_id' => 'RENTAL-SEED-1',
                'vehicle_id' => 1,
                'user_id' => 1,
                'driver_id' => 1,
                'start_book_date' => '2025-07-30',
                'end_book_date' => '2025-07-31',
                'return_date' => '2025-07-31',
                'transaction_status_id' => 1,
                'external_id' => 'RENTAL-001',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'external_id' => 'RENTAL-SEED-1',
                'vehicle_id' => 1,
                'user_id' => 1,
                'driver_id' => 1,
                'start_book_date' => '2023-06-03',
                'end_book_date' => '2023-06-04',
                'return_date' => '2023-06-05',
                'transaction_status_id' => 1,
                'external_id' => 'RENTAL-001',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'vehicle_id' => 3,
                'user_id' => 1,
                'driver_id' => NULL,
                'start_book_date' => '2023-05-03',
                'end_book_date' => '2023-05-07',
                'return_date' => '2023-05-07',
                'transaction_status_id' => 1,
                'external_id' => 'RENTAL-002',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'vehicle_id' => 5,
                'user_id' => 1,
                'driver_id' => 2,
                'start_book_date' => '2025-06-10',
                'end_book_date' => '2025-06-15',
                'return_date' => '2025-06-15',
                'transaction_status_id' => 2,
                'external_id' => 'RENTAL-003',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'vehicle_id' => 2,
                'user_id' => 1,
                'driver_id' => 4,
                'start_book_date' => '2025-06-20',
                'end_book_date' => '2025-06-25',
                'return_date' => '2025-06-25',
                'transaction_status_id' => 3,
                'external_id' => 'RENTAL-004',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'vehicle_id' => 7,
                'user_id' => 1,
                'driver_id' => NULL,
                'start_book_date' => '2025-05-10',
                'end_book_date' => '2025-05-15',
                'return_date' => '2025-05-15',
                'transaction_status_id' => 5,
                'external_id' => 'RENTAL-005',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'vehicle_id' => 1,
                'user_id' => 1,
                'driver_id' => 3,
                'start_book_date' => '2025-04-07',
                'end_book_date' => '2025-04-14',
                'return_date' => '2025-04-14',
                'transaction_status_id' => 5,
                'external_id' => 'RENTAL-006',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'vehicle_id' => 10,
                'user_id' => 1,
                'driver_id' => NULL,
                'start_book_date' => '2025-07-01',
                'end_book_date' => '2025-07-02',
                'return_date' => '2025-07-02',
                'transaction_status_id' => 5,
                'external_id' => 'RENTAL-007',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'vehicle_id' => 13,
                'user_id' => 1,
                'driver_id' => 1,
                'start_book_date' => '2025-07-06',
                'end_book_date' => '2025-07-08',
                'return_date' => '2025-07-02',
                'transaction_status_id' => 5,
                'external_id' => 'RENTAL-008',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'vehicle_id' => 6,
                'user_id' => 1,
                'driver_id' => NULL,
                'start_book_date' => '2025-01-20',
                'end_book_date' => '2025-01-22',
                'return_date' => '2025-01-22',
                'transaction_status_id' => 7,
                'external_id' => 'RENTAL-009',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'external_id' => 'RENTAL-SEED-2',
                'vehicle_id' => 2,
                'user_id' => 2,
                'driver_id' => 2,
                'start_book_date' => '2023-05-03',
                'end_book_date' => '2023-05-04',
                'return_date' => '2023-05-05',
                'transaction_status_id' => 1,
                'external_id' => 'RENTAL-010',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'external_id' => 'RENTAL-SEED-3',
                'vehicle_id' => 3,
                'user_id' => 3,
                'driver_id' => 3,
                'start_book_date' => '2023-04-03',
                'end_book_date' => '2023-04-04',
                'return_date' => '2023-04-05',
                'transaction_status_id' => 2,
                'external_id' => 'RENTAL-011',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'external_id' => 'RENTAL-SEED-4',
                'vehicle_id' => 4,
                'user_id' => 4,
                'driver_id' => 4,
                'start_book_date' => '2023-03-03',
                'end_book_date' => '2023-03-04',
                'return_date' => '2023-03-05',
                'transaction_status_id' => 3,
                'external_id' => 'RENTAL-012',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'external_id' => 'RENTAL-SEED-5',
                'vehicle_id' => 5,
                'user_id' => 5,
                'driver_id' => 5,
                'start_book_date' => '2023-02-03',
                'end_book_date' => '2023-02-04',
                'return_date' => '2023-02-05',
                'transaction_status_id' => 4,
                'external_id' => 'RENTAL-013',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'external_id' => 'RENTAL-SEED-6',
                'vehicle_id' => 6,
                'user_id' => 6,
                'driver_id' => 6,
                'start_book_date' => '2023-01-03',
                'end_book_date' => '2023-01-04',
                'return_date' => '2023-01-05',
                'transaction_status_id' => 5,
                'external_id' => 'RENTAL-014',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'external_id' => 'RENTAL-SEED-7',
                'vehicle_id' => 7,
                'user_id' => 7,
                'driver_id' => 7,
                'start_book_date' => '2022-12-03',
                'end_book_date' => '2022-12-04',
                'return_date' => '2022-12-05',
                'transaction_status_id' => 6,
                'external_id' => 'RENTAL-015',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
