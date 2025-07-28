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
            UserSeeder::class,
            DriverSeeder::class,
            VehicleSeeder::class,
            TransactionStatusSeeder::class,
            TransactionSeeder::class,
            // UserSeeder::class,

            UserReviewSeeder::class,
            CartSeeder::class,
            TransactionSeeder::class,
        ]);
    }
}
