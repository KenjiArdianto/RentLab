<?php

namespace Database\Seeders;

use App\Models\VehicleTransmission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VehicleTransmissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        VehicleTransmission::insert([
           ['transmission' => 'Automatic'],
           ['transmission' => 'Manual'],
           ['transmission' => 'Kopling'] 
        ]);
    }
}
