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
            'path' => 'https://picsum.photos/seed/picsum/1500/600',
            'isactive' => true,
        ]);
        Advertisement::factory()->create([
            'path' => 'https://picsum.photos/seed/picsum/1500/600',
            'isactive' => true,
        ]);
        Advertisement::factory()->create([
            'path' => 'https://picsum.photos/seed/picsum/1500/600',
            'isactive' => true,
        ]);
    }
}
