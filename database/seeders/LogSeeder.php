<?php

namespace Database\Seeders;

use App\Models\Log;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        Log::insert([
            [
                'actor_id' => 1,
                'actor_type' => 'user',
                'desc' => 'User logged in',
                'type' => 'login',
            ],
            [
                'actor_id' => 1,
                'actor_type' => 'user',
                'desc' => 'User logged out',
                'type' => 'logout',
            ],
            [
                'actor_id' => 2,
                'actor_type' => 'user',
                'desc' => 'User logged in',
                'type' => 'login',
            ],
            [
                'actor_id' => 2,
                'actor_type' => 'user',
                'desc' => 'User logged out',
                'type' => 'logout',
            ],
            [
                'actor_id' => 3,
                'actor_type' => 'user',
                'desc' => 'User logged in',
                'type' => 'login',
            ],
            [
                'actor_id' => 3,
                'actor_type' => 'user',
                'desc' => 'User logged out',
                'type' => 'logout',
            ]
            ]);
    }
}
