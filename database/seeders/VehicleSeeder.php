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
        Vehicle::insert([
            [
                'vehicle_type_id' => 1,
                'vehicle_name_id' => 1,
                'vehicle_transmission_id' => 1,
                'engine_cc' => 2343,
                'seats' => 2,   
                'year' => 2000,
                'location_id' => 6,
                'main_image' => 'assets/Mobil/Mobil1Main.png',
                'price' => 500000,
            ],

            [
                'vehicle_type_id' => 1,
                'vehicle_name_id' => 2,
                'vehicle_transmission_id' => 1,
                'engine_cc' => 2343,
                'seats' => 2,   
                'year' => 2000,
                'location_id' => 7,
                'main_image' => 'assets/Mobil/Mobil2Main.png',
                'price' => 383909,
            ],

            [
                'vehicle_type_id' => 1,
                'vehicle_name_id' => 3,
                'vehicle_transmission_id' => 1,
                'engine_cc' => 2343,
                'seats' => 2,   
                'year' => 2000,
                'location_id' => 8,
                'main_image' => 'assets/Mobil/Mobil3Main.png',
                'price' => 371053,
            ],

            [
                'vehicle_type_id' => 1,
                'vehicle_name_id' => 4,
                'vehicle_transmission_id' => 1,
                'engine_cc' => 2343,
                'seats' => 2,   
                'year' => 2000,
                'location_id' => 9,
                'main_image' => 'assets/Mobil/Mobil4Main.png',
                'price' => 342713,
            ],

            [
                'vehicle_type_id' => 1,
                'vehicle_name_id' => 5,
                'vehicle_transmission_id' => 1,
                'engine_cc' => 2343,
                'seats' => 2,   
                'year' => 2000,
                'location_id' => 10,
                'main_image' => 'assets/Mobil/Mobil5Main.png',
                'price' => 466580,
            ],

            [
                'vehicle_type_id' => 1,
                'vehicle_name_id' => 6,
                'vehicle_transmission_id' => 2,
                'engine_cc' => 2343,
                'seats' => 2,   
                'year' => 2000,
                'location_id' => 11,
                'main_image' => 'assets/Mobil/Mobil6Main.png',
                'price' => 111887,
            ],

            [
                'vehicle_type_id' => 1,
                'vehicle_name_id' => 7,
                'vehicle_transmission_id' => 2,
                'engine_cc' => 2343,
                'seats' => 2,   
                'year' => 2000,
                'location_id' => 12,
                'main_image' => 'assets/Mobil/Mobil7Main.png',
                'price' => 345140,
            ],

            [
                'vehicle_type_id' => 1,
                'vehicle_name_id' => 8,
                'vehicle_transmission_id' => 2,
                'engine_cc' => 2343,
                'seats' => 2,   
                'year' => 2000,
                'location_id' => 13,
                'main_image' => 'assets/Mobil/Mobil8Main.png',
                'price' => 500000,
            ],

            [
                'vehicle_type_id' => 1,
                'vehicle_name_id' => 9,
                'vehicle_transmission_id' => 2,
                'engine_cc' => 2343,
                'seats' => 2,   
                'year' => 2000,
                'location_id' => 14,
                'main_image' => 'assets/Mobil/Mobil9Main.png',
                'price' => 80865,
            ],

            [
                'vehicle_type_id' => 1,
                'vehicle_name_id' => 10,
                'vehicle_transmission_id' => 2,
                'engine_cc' => 2343,
                'seats' => 2,   
                'year' => 2000,
                'location_id' => 15,
                'main_image' => 'assets/Mobil/Mobil10Main.png',
                'price' => 210622,
            ],


            //motor

            [
                'vehicle_type_id' => 2,
                'vehicle_name_id' => 11,
                'vehicle_transmission_id' => 1,
                'engine_cc' => 150,
                'seats' => 2,   
                'year' => 2000,
                'location_id' => 1,
                'main_image' => 'assets/Motor/Motor1Main.png',
                'price' => 500000,
            ],

            [
                'vehicle_type_id' => 2,
                'vehicle_name_id' => 12,
                'vehicle_transmission_id' => 2,
                'engine_cc' => 150,
                'seats' => 2,   
                'year' => 2000,
                'location_id' => 2,
                'main_image' => 'assets/Motor/Motor2Main.png',
                'price' => 383909,
            ],

            [
                'vehicle_type_id' => 2,
                'vehicle_name_id' => 13,
                'vehicle_transmission_id' => 3,
                'engine_cc' => 150,
                'seats' => 2,   
                'year' => 2000,
                'location_id' => 10,
                'main_image' => 'assets/Motor/Motor3Main.png',
                'price' => 371053,
            ],

            [
                'vehicle_type_id' => 2,
                'vehicle_name_id' => 14,
                'vehicle_transmission_id' => 1,
                'engine_cc' => 150,
                'seats' => 2,   
                'year' => 2000,
                'location_id' => 3,
                'main_image' => 'assets/Motor/Motor4Main.png',
                'price' => 342713,
            ],

            [
                'vehicle_type_id' => 2,
                'vehicle_name_id' => 15,
                'vehicle_transmission_id' => 2,
                'engine_cc' => 150,
                'seats' => 2,   
                'year' => 2000,
                'location_id' => 4,
                'main_image' => 'assets/Motor/Motor5Main.png',
                'price' => 466580,
            ],

            [
                'vehicle_type_id' => 2,
                'vehicle_name_id' => 16,
                'vehicle_transmission_id' => 3,
                'engine_cc' => 150,
                'seats' => 2,   
                'year' => 2000,
                'location_id' => 5,
                'main_image' => 'assets/Motor/Motor6Main.png',
                'price' => 111887,
            ],

            [
                'vehicle_type_id' => 2,
                'vehicle_name_id' => 17,
                'vehicle_transmission_id' => 1,
                'engine_cc' => 150,
                'seats' => 2,   
                'year' => 2000,
                'location_id' => 6,
                'main_image' => 'assets/Motor/Motor7Main.png',
                'price' => 345140,
            ],

            [
                'vehicle_type_id' => 2,
                'vehicle_name_id' => 18,
                'vehicle_transmission_id' => 2,
                'engine_cc' => 150,
                'seats' => 2,   
                'year' => 2000,
                'location_id' => 7,
                'main_image' => 'assets/Motor/Motor8Main.png',
                'price' => 500000,
            ],

            [
                'vehicle_type_id' => 2,
                'vehicle_name_id' => 19,
                'vehicle_transmission_id' => 3,
                'engine_cc' => 150,
                'seats' => 2,   
                'year' => 2000,
                'location_id' => 8,
                'main_image' => 'assets/Motor/Motor9Main.png',
                'price' => 80865,
            ],

            [
                'vehicle_type_id' => 2,
                'vehicle_name_id' => 20,
                'vehicle_transmission_id' => 1,
                'engine_cc' => 150,
                'seats' => 2,   
                'year' => 2000,
                'location_id' => 9,
                'main_image' => 'assets/Motor/Motor10Main.png',
                'price' => 210622,
            ],
        ]);


        for ($i = 1; $i <= 5; $i++) {
                $vehicle = Vehicle::find($i);
                $vehicle->vehicleCategories()->attach(id: [1, $i]);
        }

        for ($i = 5; $i <= 10; $i++) {
                $vehicle = Vehicle::find($i);
                $vehicle->vehicleCategories()->attach(id: [$i]);
        }

        for ($i = 11; $i <= 20; $i++) {
                $vehicle = Vehicle::find($i);
                $vehicle->vehicleCategories()->attach(id: [$i]);
        }

        $vehicle = Vehicle::find(1);
        $vehicle->vehicleImages()->create(['image' => 'assets/Mobil/Mobil1 (1).png']);
        $vehicle->vehicleImages()->create(['image' => 'assets/Mobil/Mobil1 (2).png']);
        $vehicle->vehicleImages()->create(['image' => 'assets/Mobil/Mobil1 (3).png']);
        $vehicle->vehicleImages()->create(['image' => 'assets/Mobil/Mobil1 (4).png']);

        $vehicle = Vehicle::find(2);
        $vehicle->vehicleImages()->create(['image' => 'assets/Mobil/Mobil2 (1).png']);
        $vehicle->vehicleImages()->create(['image' => 'assets/Mobil/Mobil2 (2).png']);
        $vehicle->vehicleImages()->create(['image' => 'assets/Mobil/Mobil2 (3).png']);
        $vehicle->vehicleImages()->create(['image' => 'assets/Mobil/Mobil2 (4).png']);

        $vehicle = Vehicle::find(3);
        $vehicle->vehicleImages()->create(['image' => 'assets/Mobil/Mobil3 (1).png']);
        $vehicle->vehicleImages()->create(['image' => 'assets/Mobil/Mobil3 (2).png']);
        $vehicle->vehicleImages()->create(['image' => 'assets/Mobil/Mobil3 (3).png']);
        $vehicle->vehicleImages()->create(['image' => 'assets/Mobil/Mobil3 (4).png']);

        $vehicle = Vehicle::find(4);
        $vehicle->vehicleImages()->create(['image' => 'assets/Mobil/Mobil4 (1).png']);
        $vehicle->vehicleImages()->create(['image' => 'assets/Mobil/Mobil4 (2).png']);
        $vehicle->vehicleImages()->create(['image' => 'assets/Mobil/Mobil4 (3).png']);
        $vehicle->vehicleImages()->create(['image' => 'assets/Mobil/Mobil4 (4).png']);

        $vehicle = Vehicle::find(5);
        $vehicle->vehicleImages()->create(['image' => 'assets/Mobil/Mobil5 (1).png']);
        $vehicle->vehicleImages()->create(['image' => 'assets/Mobil/Mobil5 (2).png']);
        $vehicle->vehicleImages()->create(['image' => 'assets/Mobil/Mobil5 (3).png']);
        $vehicle->vehicleImages()->create(['image' => 'assets/Mobil/Mobil5 (4).png']);

        $vehicle = Vehicle::find(6);
        $vehicle->vehicleImages()->create(['image' => 'assets/Mobil/Mobil6 (1).png']);
        $vehicle->vehicleImages()->create(['image' => 'assets/Mobil/Mobil6 (2).png']);
        $vehicle->vehicleImages()->create(['image' => 'assets/Mobil/Mobil6 (3).png']);
        $vehicle->vehicleImages()->create(['image' => 'assets/Mobil/Mobil6 (4).png']);
        
        $vehicle = Vehicle::find(7);
        $vehicle->vehicleImages()->create(['image' => 'assets/Mobil/Mobil7 (1).png']);
        $vehicle->vehicleImages()->create(['image' => 'assets/Mobil/Mobil7 (2).png']);
        $vehicle->vehicleImages()->create(['image' => 'assets/Mobil/Mobil7 (3).png']);
        $vehicle->vehicleImages()->create(['image' => 'assets/Mobil/Mobil7 (4).png']);

        $vehicle = Vehicle::find(8);
        $vehicle->vehicleImages()->create(['image' => 'assets/Mobil/Mobil8 (1).png']);
        $vehicle->vehicleImages()->create(['image' => 'assets/Mobil/Mobil8 (2).png']);
        $vehicle->vehicleImages()->create(['image' => 'assets/Mobil/Mobil8 (3).png']);
        $vehicle->vehicleImages()->create(['image' => 'assets/Mobil/Mobil8 (4).png']);

        $vehicle = Vehicle::find(9);
        $vehicle->vehicleImages()->create(['image' => 'assets/Mobil/Mobil9 (1).png']);
        $vehicle->vehicleImages()->create(['image' => 'assets/Mobil/Mobil9 (2).png']);
        $vehicle->vehicleImages()->create(['image' => 'assets/Mobil/Mobil9 (3).png']);
        $vehicle->vehicleImages()->create(['image' => 'assets/Mobil/Mobil9 (4).png']);

        $vehicle = Vehicle::find(10);
        $vehicle->vehicleImages()->create(['image' => 'assets/Mobil/Mobil10 (1).png']);
        $vehicle->vehicleImages()->create(['image' => 'assets/Mobil/Mobil10 (2).png']);
        $vehicle->vehicleImages()->create(['image' => 'assets/Mobil/Mobil10 (3).png']);
        $vehicle->vehicleImages()->create(['image' => 'assets/Mobil/Mobil10 (4).png']);

        $vehicle = Vehicle::find(11);
        $vehicle->vehicleImages()->create(['image' => 'assets/Mobil/Mobil11 (1).png']);
        $vehicle->vehicleImages()->create(['image' => 'assets/Mobil/Mobil11 (2).png']);
        $vehicle->vehicleImages()->create(['image' => 'assets/Mobil/Mobil11 (3).png']);
        $vehicle->vehicleImages()->create(['image' => 'assets/Mobil/Mobil11 (4).png']);

        $vehicle = Vehicle::find(12);
        $vehicle->vehicleImages()->create(['image' => 'assets/Mobil/Mobil12 (1).png']);
        $vehicle->vehicleImages()->create(['image' => 'assets/Mobil/Mobil12 (2).png']);
        $vehicle->vehicleImages()->create(['image' => 'assets/Mobil/Mobil12 (3).png']);
        $vehicle->vehicleImages()->create(['image' => 'assets/Mobil/Mobil12 (4).png']);

        $vehicle = Vehicle::find(13);
        $vehicle->vehicleImages()->create(['image' => 'assets/Mobil/Mobil13 (1).png']);
        $vehicle->vehicleImages()->create(['image' => 'assets/Mobil/Mobil13 (2).png']);
        $vehicle->vehicleImages()->create(['image' => 'assets/Mobil/Mobil13 (3).png']);
        $vehicle->vehicleImages()->create(['image' => 'assets/Mobil/Mobil13 (4).png']);

        $vehicle = Vehicle::find(14);
        $vehicle->vehicleImages()->create(['image' => 'assets/Mobil/Mobil14 (1).png']);
        $vehicle->vehicleImages()->create(['image' => 'assets/Mobil/Mobil14 (2).png']);
        $vehicle->vehicleImages()->create(['image' => 'assets/Mobil/Mobil14 (3).png']);
        $vehicle->vehicleImages()->create(['image' => 'assets/Mobil/Mobil14 (4).png']);

        $vehicle = Vehicle::find(15);
        $vehicle->vehicleImages()->create(['image' => 'assets/Mobil/Mobil15 (1).png']);
        $vehicle->vehicleImages()->create(['image' => 'assets/Mobil/Mobil15 (2).png']);
        $vehicle->vehicleImages()->create(['image' => 'assets/Mobil/Mobil15 (3).png']);
        $vehicle->vehicleImages()->create(['image' => 'assets/Mobil/Mobil15 (4).png']);

        $vehicle = Vehicle::find(16);
        $vehicle->vehicleImages()->create(['image' => 'assets/Mobil/Mobil16 (1).png']);
        $vehicle->vehicleImages()->create(['image' => 'assets/Mobil/Mobil16 (2).png']);
        $vehicle->vehicleImages()->create(['image' => 'assets/Mobil/Mobil16 (3).png']);
        $vehicle->vehicleImages()->create(['image' => 'assets/Mobil/Mobil16 (4).png']);

        $vehicle = Vehicle::find(17);
        $vehicle->vehicleImages()->create(['image' => 'assets/Mobil/Mobil7 (1).png']);
        $vehicle->vehicleImages()->create(['image' => 'assets/Mobil/Mobil7 (2).png']);
        $vehicle->vehicleImages()->create(['image' => 'assets/Mobil/Mobil7 (3).png']);
        $vehicle->vehicleImages()->create(['image' => 'assets/Mobil/Mobil7 (4).png']);

        $vehicle = Vehicle::find(18);
        $vehicle->vehicleImages()->create(['image' => 'assets/Mobil/Mobil18 (1).png']);
        $vehicle->vehicleImages()->create(['image' => 'assets/Mobil/Mobil18 (2).png']);
        $vehicle->vehicleImages()->create(['image' => 'assets/Mobil/Mobil18 (3).png']);
        $vehicle->vehicleImages()->create(['image' => 'assets/Mobil/Mobil18 (4).png']);

        $vehicle = Vehicle::find(19);
        $vehicle->vehicleImages()->create(['image' => 'assets/Mobil/Mobil19 (1).png']);
        $vehicle->vehicleImages()->create(['image' => 'assets/Mobil/Mobil19 (2).png']);
        $vehicle->vehicleImages()->create(['image' => 'assets/Mobil/Mobil19 (3).png']);
        $vehicle->vehicleImages()->create(['image' => 'assets/Mobil/Mobil19 (4).png']);

        $vehicle = Vehicle::find(20);
        $vehicle->vehicleImages()->create(['image' => 'assets/Mobil/Mobil20 (1).png']);
        $vehicle->vehicleImages()->create(['image' => 'assets/Mobil/Mobil20 (2).png']);
        $vehicle->vehicleImages()->create(['image' => 'assets/Mobil/Mobil20 (3).png']);
        $vehicle->vehicleImages()->create(['image' => 'assets/Mobil/Mobil20 (4).png']);



    }
}
