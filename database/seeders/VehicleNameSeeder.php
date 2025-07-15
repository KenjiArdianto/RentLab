<?php

namespace Database\Seeders;

use App\Models\VehicleName;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VehicleNameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        VehicleName::insert([
            ['name' => 'Honda Civic'],
            ['name' => 'Toyota Corolla'],
            ['name' => 'Tesla Model 3'],
            ['name' => 'Ford Mustang'],
            ['name' => 'Chevrolet Malibu'],
            ['name' => 'BMW 3 Series'],
            ['name' => 'Mercedes-Benz C-Class'],
            ['name' => 'Hyundai Elantra'],
            ['name' => 'Mazda 3'],
            ['name' => 'Audi A4'],
            ['name' => 'Honda Vario'],
            ['name' => 'Yamaha NMAX'],
            ['name' => 'Suzuki Satria F150'],
            ['name' => 'Kawasaki Ninja 250'],
            ['name' => 'Honda PCX'],
            ['name' => 'Yamaha Aerox'],
            ['name' => 'Vespa Primavera'],
            ['name' => 'Royal Enfield Classic 350'],
            ['name' => 'Ducati Monster 797'],
            ['name' => 'KTM Duke 200'],
        ]);
    }
}
