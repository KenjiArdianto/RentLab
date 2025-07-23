<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Generator as FakerGenerator;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // 1. Dapatkan instance Faker dari service container Laravel
        $faker = app(FakerGenerator::class);

        // 2. Atur seed untuk Faker dengan angka yang sudah Anda pilih
        //    Anda bisa mengkondisikan ini hanya untuk environment tertentu jika perlu
        if (app()->environment(['local', 'testing', 'demo'])) { // Opsional: sesuaikan environment
            $faker->seed(12345); // GANTI 12345 DENGAN ANGKA SEED PILIHAN ANDA
        }

        $this->call([
            // DriverSeeder::class,
            // VehicleSeeder::class,
            // CartSeeder::class,
            // RatingSeeder::class,
            // LogSeeder::class,
            AdvertisementSeeder::class,
            VehicleNameSeeder::class,
            VehicleTypeSeeder::class,
            VehicleCategorySeeder::class,
            VehicleTransmissionSeeder::class,
            LocationSeeder::class,
            
            DriverSeeder::class,
            VehicleSeeder::class,
            TransactionStatusSeeder::class,
            TransactionSeeder::class,
            // UserSeeder::class,

            // CartSeeder::class,
            // TransactionSeeder::class,


        ]);
    }
}
