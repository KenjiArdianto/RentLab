<?php

namespace Database\Seeders;

use App\Models\Driver;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DriverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Driver::insert([
            [
                'name' => 'Driver 1',
                'image' => 'images/driver1.jpg',
                'active_since' => '2025-06-08',
            ],
            [
                'name' => 'Driver 2',
                'image' => 'images/driver2.jpg',
                'active_since' => '2025-06-07',
            ],
            [
                'name' => 'Driver 3',
                'image' => 'images/driver3.jpg',
                'active_since' => '2025-06-06',
            ],
            [
                'name' => 'Driver 4',
                'image' => 'images/driver4.jpg',
                'active_since' => '2025-06-05',
            ],
            [
                'name' => 'Driver 5',
                'image' => 'images/driver5.jpg',
                'active_since' => '2025-06-04',
            ],
            [
                'name' => 'Driver 6',
                'image' => 'images/driver6.jpg',
                'active_since' => '2025-06-03',
            ],
            [
                'name' => 'Driver 7',
                'image' => 'images/driver7.jpg',
                'active_since' => '2025-06-02',
            ],
            [
                'name' => 'Driver 8',
                'image' => 'images/driver8.jpg',
                'active_since' => '2025-06-01',
            ],
        ]);
    }
}
    