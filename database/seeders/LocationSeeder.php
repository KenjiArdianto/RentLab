<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Location::insert([
           ['location' => 'Jakarta'],
            ['location' => 'Bandung'],
            ['location' => 'Surabaya'],
            ['location' => 'Semarang'],
            ['location' => 'Yogyakarta'],
            ['location' => 'Bogor'],
            ['location' => 'Depok'],
            ['location' => 'Bekasi'],
            ['location' => 'Tangerang'],
            ['location' => 'Malang'],
            ['location' => 'Medan'],
            ['location' => 'Palembang'],
            ['location' => 'Makassar'],
            ['location' => 'Balikpapan'],
            ['location' => 'Banjarmasin'],
        ]);
    }
}
