<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserReview;
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
        DB::table('user_reviews')->insert([
            [
                'transaction_id' => 5,
                'user_id' => 1,
                'admin_id' => 1,
                'rate' => 5,
                'comment' => 'Pengguna sangat kooperatif dan mengembalikan kendaraan tepat waktu.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'transaction_id' => 8,
                'user_id' => 1,
                'admin_id' => 1,
                'rate' => 5,
                'comment' => 'Pengguna sangat kooperatif, kendaraan tetap bersih dan mengembalikan kendaraan tepat waktu.',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
        UserReview::insert([
            ['admin_id' => 1,
            'user_id'=>1,
            'transaction_id'=>1,
            'comment'=> "Mobil yang saya pinjam benar-benar memenuhi ekspektasi. Performa mesinnya tangguh, handling-nya stabil, dan sangat irit bahan bakar. Interior bersih dan wangi, membuat perjalanan terasa nyaman. Cocok banget buat liburan keluarga!",
            'rate'=>4,
            ],
            ['admin_id' => 2,
            'user_id'=>2,
            'transaction_id'=>2,
            'comment'=> "Saya menggunakan mobil ini untuk keperluan dinas luar kota, dan hasilnya memuaskan. Tidak ada kendala sama sekali selama perjalanan. Fitur-fitur mobil juga lengkap, mulai dari Bluetooth, kamera mundur, sampai cruise control semuanya berfungsi dengan baik.",
            'rate'=>3,
            ],
            ['admin_id' => 3,
            'user_id'=>3,
            'transaction_id'=>3,
            'comment'=> "Kondisi mobil sangat prima, terasa seperti mengendarai mobil pribadi sendiri. AC dingin, rem pakem, dan suspensinya empuk, cocok untuk jalanan kota yang padat maupun medan luar kota. Proses peminjamannya juga sangat praktis dan cepat.",
            'rate'=>2,
            ],
            ['admin_id' => 4,
            'user_id'=>4,
            'transaction_id'=>4,
            'comment'=> "Saya senang sekali bisa menemukan layanan sebaik ini. Mobil yang saya dapatkan tidak hanya bersih luar dalam, tapi juga terlihat baru dan terawat. Tidak ada suara aneh dari mesin dan konsumsi bahan bakarnya juga efisien. Top banget!",
            'rate'=>4,
            ],
            ['admin_id' => 5,
            'user_id'=>5,
            'transaction_id'=>5,
            'comment'=> "Ini pertama kalinya saya menyewa mobil, dan pengalaman saya sungguh menyenangkan. Mobilnya nyaman, kabin luas, cocok untuk perjalanan keluarga. Pelayanan customer service juga sangat membantu, ramah, dan informatif.",
            'rate'=>2,
            ],
            ['admin_id' => 6,
            'user_id'=>6,
            'transaction_id'=>7,
            'comment'=> "Mobil pinjaman ini benar-benar membantu saya saat harus menghadiri acara penting di luar kota. Mobilnya tidak hanya nyaman, tapi juga stylish. Sangat layak digunakan di berbagai situasi formal maupun santai. Akan saya gunakan lagi kalau ada kesempatan.",
            'rate'=>5,
            ],
        ]);
    }
}
