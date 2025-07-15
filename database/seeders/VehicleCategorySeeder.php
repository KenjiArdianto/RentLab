<?php

namespace Database\Seeders;

use App\Models\VehicleCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VehicleCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        VehicleCategory::insert([
            ['category' => 'Sedan'],
            ['category' => 'SUV'],
            ['category' => 'Hatchback'],
            ['category' => 'Convertible'],
            ['category' => 'Coupe'],
            ['category' => 'Wagon'],
            ['category' => 'Pickup'],
            ['category' => 'Van'],
            ['category' => 'Electric'],
            ['category' => 'Hybrid'],
            ['category' => 'Luxury'],
            ['category' => 'Off-Road'],
            ['category' => 'Sport'],
            ['category' => 'City'],
            ['category' => 'Custom'],
            ['category' => 'Scooter'],
            ['category' => 'Moped'],
            ['category' => 'Sport'],
            ['category' => 'Cruiser'],
            ['category' => 'Touring'],
            ['category' => 'Off-Road'],
            ['category' => 'Standard'],
            ['category' => 'Cafe Racer'],
            ['category' => 'Adventure'],
            ['category' => 'Electric'],
            ['category' => 'Custom'],
            ['category' => 'Naked'],
            ['category' => 'Commuter'],
            ['category' => 'Mini Bike'],
        ]);
    }
}
