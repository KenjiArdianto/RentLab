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
        $user=$this->create_test_user()->fresh();
        $response = $this->actingAs($user)->get('/complete-user-detail');
        $response->assertStatus(200);
    }

    /** @test */
    public function tc002_guest_cannot_access_user_detail_page()
    {
        $response = $this->get('/complete-user-detail');
        $response->assertRedirect('/login');
    }

    /** @test */
    public function tc003_admin_cannot_access_user_detail_page()
    {
        $this->delete_test_user();
        $admin = $this->create_test_user(['role'=>'admin'])->fresh();
        $response = $this->actingAs($admin)->get('/complete-user-detail');
        $response->assertStatus(403);
        $response->assertSee('bukan user ga usah maksa');
    }

    /** @test */
    public function tc004_redirect_if_user_already_has_detail()
    {
        $this->delete_test_user();
        $user=$this->create_test_user()->fresh();
        $user->detail()->createOrFirst([
            'fname' => 'Jane',
            'lname' => 'Doe',
            'phoneNumber' => '08123456789',
            'idcardNumber' => '9876543210123456',
            'dateOfBirth' => '1990-01-01',
            'idcardPicture' => 'idcard/fakepic.jpg'
        ]);

        // dd($user->role);

        $response = $this->from('/home')->actingAs($user)->get('/complete-user-detail');
        $response->assertRedirect('/home');
    }

     /** @test */
    public function tc005_submit_empty_form_shows_validation_errors()
    {
        $user=$this->create_test_user()->fresh();
        $response = $this->actingAs($user)->post('/complete-user-detail', []);
        $response->assertSessionHasErrors(['fname', 'lname', 'phoneNumber', 'idcardNumber', 'dateOfBirth', 'idcardPicture']);
    }

    /** @test */
    public function tc006_submit_invalid_names()
    {
        $user=$this->create_test_user()->fresh();
        $response = $this->actingAs($user)->post('/complete-user-detail', [
            'fname' => 'Jane123',
            'lname' => 'Doe!',
            'phoneNumber' => '08123456789',
            'idcardNumber' => '9876543210123456',
            'dateOfBirth' => '1990-01-01',
            'idcardPicture' => 'idcard/fakepic.jpg'
        ]);
        $response->assertSessionHasErrors(['fname', 'lname']);
    }

    /** @test */
    public function tc007_submit_invalid_phone_length()
    {
        $user=$this->create_test_user()->fresh();
        $response = $this->actingAs($user)->post('/complete-user-detail', [
            'fname' => 'Jane123',
            'lname' => 'Doe!',
            'phoneNumber' => '1234567',
            'idcardNumber' => '9876543210123456',
            'dateOfBirth' => '1990-01-01',
            'idcardPicture' => 'idcard/fakepic.jpg'
        ]);
        $response->assertSessionHasErrors(['phoneNumber']);

        $response = $this->actingAs($user)->post('/complete-user-detail', [
            'fname' => 'Jane123',
            'lname' => 'Doe!',
            'phoneNumber' => '12345678901234',
            'idcardNumber' => '9876543210123456',
            'dateOfBirth' => '1990-01-01',
            'idcardPicture' => 'idcard/fakepic.jpg'
        ]);
        $response->assertSessionHasErrors(['phoneNumber']);
    }

    /** @test */
    public function tc008_submit_invalid_date_of_birth()
    {
        $user=$this->create_test_user()->fresh();
        $response = $this->actingAs($user)->post('/complete-user-detail', [
            'fname' => 'Jane123',
            'lname' => 'Doe!',
            'phoneNumber' => '123456789',
            'idcardNumber' => '9876543210123456',
            'dateOfBirth' => 'not-a-date',
            'idcardPicture' => 'idcard/fakepic.jpg'
        ]);
        $response->assertSessionHasErrors(['dateOfBirth']);
    }

    /** @test */
    public function tc011_submit_invalid_date_of_birth()
    {
        $user=$this->create_test_user()->fresh();
        $response = $this->actingAs($user)->post('/complete-user-detail', [
            'fname' => 'Jane',
            'lname' => 'Doe',
            'phoneNumber' => '08123456789',
            'idcardNumber' => '9876543210123456',
            'dateOfBirth' => '1990-01-01',
            'idcardPicture' => 'idcard/fakepic.jpg'
        ]);
        $response->assertRedirect('/home');
    }

}
