<?php

namespace Database\Seeders;

use App\Models\Vehicle;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Vehicle::factory()->count(100)->create();
        //
        // Vehicle::insert([
        //     [
        //         'name' => 'Honda Civic',
        //         'price' => '2000000',
        //         'year' => '2023',
        //         'image' => 'images/hondacivic.jpg',
        //         'engine_cc' => '2100',
        //         'transmission_type' => 'Matic',
        //         'type' => 'Mobil',
        //     ],
        //     [
        //         'name' => 'Toyota Corolla',
        //         'price' => '3000000',
        //         'year' => '2022',
        //         'image' => 'images/toyotacorolla.jpg',
        //         'engine_cc' => '2000',
        //         'transmission_type' => 'Manual',
        //         'type' => 'Mobil',
        //     ],
        //     [
        //         'name' => 'Mazda 3',
        //         'price' => '2500000',
        //         'year' => '2021',
        //         'image' => 'images/mazda3.jpg',
        //         'engine_cc' => '1900',
        //         'transmission_type' => 'Kopling',
        //         'type' => 'Mobil',
        //     ],
        //     [
        //         'name' => 'Yamaha R1',
        //         'price' => '4000000',
        //         'year' => '2023',
        //         'image' => 'images/yamahar1.jpg',
        //         'engine_cc' => '1000',
        //         'transmission_type' => 'Manual',
        //         'type' => 'Motor',
        //     ],
        //     [
        //         'name' => 'Ducati Panigale',
        //         'price' => '3500000',
        //         'year' => '2022',
        //         'image' => 'images/ducatipanigale.jpg',
        //         'engine_cc' => '1100',
        //         'transmission_type' => 'Matic',
        //         'type' => 'Motor',
        //     ],
        //     [
        //         'name' => 'Honda CBR',
        //         'price' => '3000000',
        //         'year' => '2021',
        //         'image' => 'images/hondacbr.jpg',
        //         'engine_cc' => '900',
        //         'transmission_type' => 'Kopling',
        //         'type' => 'Motor',
        //     ]
        // ]);
    }
}
