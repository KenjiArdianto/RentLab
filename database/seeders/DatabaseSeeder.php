<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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

            // CartSeeder::class,
            // TransactionSeeder::class,


        ]);
    }
}
