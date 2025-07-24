<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserReview;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        UserReview::insert([
            ['admin_id' => 1,
            'user_id'=>1,
            'transaction_id'=>1,
            'comment'=> "comment 1 Lorem ipsum dolor sit amet consectetur adipisicing elit. Exercitationem, laboriosam nulla! Odio voluptatem, distinctio cupiditate facere iste eum quia nulla minus totam quaerat mollitia quas culpa eligendi, veniam, tempore doloribus!",
            'rate'=>4,
            ],
            ['admin_id' => 2,
            'user_id'=>2,
            'transaction_id'=>2,
            'comment'=> "comment 2 Lorem ipsum dolor sit amet consectetur adipisicing elit. Exercitationem, laboriosam nulla! Odio voluptatem, distinctio cupiditate facere iste eum quia nulla minus totam quaerat mollitia quas culpa eligendi, veniam, tempore doloribus!",
            'rate'=>3,
            ],
            ['admin_id' => 3,
            'user_id'=>3,
            'transaction_id'=>3,
            'comment'=> "comment 3 Lorem ipsum dolor sit amet consectetur adipisicing elit. Exercitationem, laboriosam nulla! Odio voluptatem, distinctio cupiditate facere iste eum quia nulla minus totam quaerat mollitia quas culpa eligendi, veniam, tempore doloribus!",
            'rate'=>2,
            ],
            ['admin_id' => 4,
            'user_id'=>4,
            'transaction_id'=>4,
            'comment'=> "comment 4 Lorem ipsum dolor sit amet consectetur adipisicing elit. Exercitationem, laboriosam nulla! Odio voluptatem, distinctio cupiditate facere iste eum quia nulla minus totam quaerat mollitia quas culpa eligendi, veniam, tempore doloribus!",
            'rate'=>4,
            ],
            ['admin_id' => 5,
            'user_id'=>5,
            'transaction_id'=>5,
            'comment'=> "comment 5 Lorem ipsum dolor sit amet consectetur adipisicing elit. Exercitationem, laboriosam nulla! Odio voluptatem, distinctio cupiditate facere iste eum quia nulla minus totam quaerat mollitia quas culpa eligendi, veniam, tempore doloribus!",
            'rate'=>2,
            ],
            ['admin_id' => 6,
            'user_id'=>6,
            'transaction_id'=>7,
            'comment'=> "comment 7 Lorem ipsum dolor sit amet consectetur adipisicing elit. Exercitationem, laboriosam nulla! Odio voluptatem, distinctio cupiditate facere iste eum quia nulla minus totam quaerat mollitia quas culpa eligendi, veniam, tempore doloribus!",
            'rate'=>5,
            ],
        ]);
    }
}
