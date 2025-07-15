<?php

namespace Database\Seeders;

use App\Models\Advertisement;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdvertisementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Advertisement::factory()->create([
            'path' => '/advertisement/1.png',
            'isactive' => true,
        ]);
        Advertisement::factory()->create([
            'path' => '/advertisement/2.png',
            'isactive' => true,
        ]);
        Advertisement::factory()->create([
            'path' => '/advertisement/3.png',
            'isactive' => true,
        ]);
    }
}
