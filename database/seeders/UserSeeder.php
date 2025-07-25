<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserDetail;
use Faker\Factory;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // Array of users with detail info
        $users = [
            [
                'name' => 'TralaleroTralala',
                'email' => 'tralalerotralala@example.com',
                'fname' => 'Tralalero',
                'lname' => 'Tralala',
                'phone' => '0800000001',
                'idcard' => '1111222233334444',
                'dob' => '2000-01-01',
                'profilePicture' => 'assets\users\picture_profile_user_2.png',
            ],
            [
                'name' => 'BombardiroCrocodilo',
                'email' => 'bombardirocrocodilo@example.com',
                'fname' => 'Bombardiro',
                'lname' => 'Crocodilo',
                'phone' => '0800000002',
                'idcard' => '2222333344445555',
                'dob' => '2000-01-02',
                'profilePicture' => 'assets\users\picture_profile_user_3.png',
            ],
            [
                'name' => 'TungTungTungSahur',
                'email' => 'tungtungtungsahur@example.com',
                'fname' => 'TungTungTung',
                'lname' => 'Sahur',
                'phone' => '0800000003',
                'idcard' => '3333444455556666',
                'dob' => '2000-01-03',
                'profilePicture' => 'assets\users\picture_profile_user_4.png',
            ],
            [
                'name' => 'LiriliLarila',
                'email' => 'lirililarila@example.com',
                'fname' => 'Lirili',
                'lname' => 'Larila',
                'phone' => '0800000004',
                'idcard' => '4444555566667777',
                'dob' => '2000-01-04',
                'profilePicture' => 'assets\users\picture_profile_user_5.png',
            ],
            [
                'name' => 'BonecaAmbalabu',
                'email' => 'bonecaambalabu@example.com',
                'fname' => 'Boneca',
                'lname' => 'Ambalabu',
                'phone' => '0800000005',
                'idcard' => '5555666677778888',
                'dob' => '2000-01-05',
                'profilePicture' => 'assets\users\picture_profile_user_6.png',
            ],
            [
                'name' => 'BrrBrrPatapim',
                'email' => 'brrbrrpatapim@example.com',
                'fname' => 'BrrBrr',
                'lname' => 'Patapim',
                'phone' => '0800000006',
                'idcard' => '6666777788889999',
                'dob' => '2000-01-06',
                'profilePicture' => 'assets\users\picture_profile_user_7.png',
            ],
            [
                'name' => 'ChimpanziniBananini',
                'email' => 'chimpanzinibananini@example.com',
                'fname' => 'Chimpanzini',
                'lname' => 'Bananini',
                'phone' => '0800000007',
                'idcard' => '7777888899990000',
                'dob' => '2000-01-07',
                'profilePicture' => 'assets\users\picture_profile_user_8.png',
            ],
            [
                'name' => 'BombombiniGusini',
                'email' => 'bombombinigusini@example.com',
                'fname' => 'Bombombini',
                'lname' => 'Gusini',
                'phone' => '0800000008',
                'idcard' => '8888999900001111',
                'dob' => '2000-01-08',
                'profilePicture' => 'assets\users\picture_profile_user_9.png',
            ],
            [
                'name' => 'CapuccinoAssasino',
                'email' => 'capuccinoassasino@example.com',
                'fname' => 'Capuccino',
                'lname' => 'Assasino',
                'phone' => '0800000009',
                'idcard' => '9999000011112222',
                'dob' => '2000-01-09',
                'profilePicture' => 'assets\users\picture_profile_user_10.png',
            ],
            [
                'name' => 'BallerinaCappucina',
                'email' => 'ballerinacappucina@example.com',
                'fname' => 'Ballerina',
                'lname' => 'Cappucina',
                'phone' => '0800000010',
                'idcard' => '0000111122223333',
                'dob' => '2000-01-10',
                'profilePicture' => 'assets\users\picture_profile_user_11.png',
            ],
        ];

        $faker = Factory::create();

        // Loop to create users and details
        foreach ($users as $u) {
            $user = User::create([
                'name' => $u['name'],
                'email' => $u['email'],
                'password' => $faker->password(),
                'role' => 'user',
            ]);

            UserDetail::create([
                'user_id' => $user->id,
                'fname' => $u['fname'],
                'lname' => $u['lname'],
                'phoneNumber' => $u['phone'],
                'idcardNumber' => $u['idcard'],
                'dateOfBirth' => $u['dob'],
                'idcardPicture' => 'picture_profile_user_default.jpg',
                'profilePicture' => $u['profilePicture'],
            ]);
        }

        User::create([
            'name' => 'Esquie',
            'email' => 'esquie@example',
            'password' => $faker->password(),
            'role' => 'admin',
        ]);
    }
}
