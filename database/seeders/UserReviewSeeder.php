<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('user_review')->insert([
            // Review dari Admin untuk transaksi ID 4 (yang statusnya "Review By Admin")
            [
                'transaction_id' => 5,
                'user_id' => 1,         // Pengguna yang direview
                'admin_id' => 1,        // Admin yang mereview (misal: Test User)
                'rate' => 5,
                'comment' => 'Pengguna sangat kooperatif dan mengembalikan kendaraan tepat waktu.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'transaction_id' => 8,
                'user_id' => 1,         // Pengguna yang direview
                'admin_id' => 1,        // Admin yang mereview (misal: Test User)
                'rate' => 5,
                'comment' => 'Pengguna sangat kooperatif, kendaraan tetap bersih dan mengembalikan kendaraan tepat waktu.',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
