<?php

namespace Tests\Feature\auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class userDetailTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    // use RefreshDatabase;
    public function create_test_user(array $overrides=[]){
        
        return User::updateOrCreate([
            'email'=>'testUserDetail@gmail.com'
        ],array_merge([
            'name'=>'testUserDetail',
            'email'=>'testUserDetail@gmail.com',
            'password'=>Hash::make('password123'),
            'email_verified_at'=>now(),
        ],$overrides));
    }

    /** @test */
    public function delete_test_user(){
        $user = User::where('email',"=",'testUserDetail@gmail.com')->first();
        if($user){
            $user->delete();
            $this->assertSoftDeleted('users',['email'=>'testUserDetail@gmail.com']);
        }else{
            echo "User doesn't exist to delete";
            $this->assertTrue(true,"User doesn't exist to delete");
        }
    }   

     /** @test */
    public function tc001_access_user_detail_page_as_intended_user()
    {
        $user=$this->create_test_user();
        $response = $this->actingAs($user)->get('/complete-user-detail');
        $response->assertStatus(200);
    }

    /** @test */
    public function tc002_guest_cannot_access_user_detail_page()
    {
        $response = $this->get('/complete-user-detail');
        $response->assertRedirect('/login');
    }
}
