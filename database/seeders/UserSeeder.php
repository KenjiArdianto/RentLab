<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::insert([
            [
                'id' => 1,
                'name' => 'Admin 1',
                'email' => 'admin1@example.com',
                'password' => 'asdkjaskdja',
            ],
            [
                'id' => 2,
                'name' => 'Admin 2',
                'email' => 'admin2@example.com',
                'password' => 'asdkjaskdja',
            ],
            [
                'id' => 3,
                'name' => 'Admin 3',
                'email' => 'admin3@example.com',
                'password' => 'asdkjaskdja',
            ],
            [
                'id' => 4,
                'name' => 'Admin 4',
                'email' => 'admin4@example.com',
                'password' => 'asdkjaskdja',
            ],
            [
                'id' => 5,
                'name' => 'Admin 5',
                'email' => 'admin5@example.com',
                'password' =>'asdkjaskdja',
            ],
            [
                'id' => 6,
                'name' => 'Admin 6',
                'email' => 'admin6@example.com',
                'password' => 'asdkjaskdja',
            ],
        ]);

    }
}
