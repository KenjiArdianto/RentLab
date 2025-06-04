<?php

namespace Database\Seeders;

use App\Models\Rating;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RatingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Rating::insert([
            [
                'reviewer_id' => 1,
                'reviewed_id' => 5,
                'transaction_id' => 1,
                'type' => 'user',
                'rating' => 5,
                'comment' => 'User was great',
            ],
            [
                'reviewer_id' => 2,
                'reviewed_id' => 4,
                'transaction_id' => 2,
                'type' => 'vehicle',
                'rating' => 4,
                'comment' => 'Vehicle was good',
            ],
            [
                'reviewer_id' => 3,
                'reviewed_id' => 3,
                'transaction_id' => 3,
                'type' => 'user',
                'rating' => 3,
                'comment' => 'User was okay',
            ],
            [
                'reviewer_id' => 4,
                'reviewed_id' => 2,
                'transaction_id' => 4,
                'type' => 'vehicle',
                'rating' => 2,
                'comment' => 'Vehicle was bad',
            ],
            [
                'reviewer_id' => 5,
                'reviewed_id' => 1,
                'transaction_id' => 5,
                'type' => 'user',
                'rating' => 1,
                'comment' => 'User was terrible',
            ]
        ]);
    }
}
