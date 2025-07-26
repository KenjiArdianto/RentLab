<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Membuat user utama sesuai screenshot Anda
        \App\Models\User::updateOrCreate(
['email' => 'test@example.com'], 
    [
                'name' => 'Test User',
                'password' => Hash::make('password'),
                'email_verified_at' => now()
            ]
        );

        // Membuat 9 user lainnya secara acak
        \App\Models\User::factory(9)->create();
    }
}