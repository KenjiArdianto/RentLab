<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class RegisterTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    protected string $redirectUrl="/home";
    protected string $username="testRegister";
    protected string $email="testRegisterEmail@gmail.com";
    protected string $password="password123";

    /** @test */
    public function get_page(){
        $response = $this->get('/register');
        $response -> assertStatus(200);
    }

    /** @test */
    public function successful_registration_attempt(){
        $response=$this->from($this->redirectUrl)->post('/register',[
            'name'=>$this->username,
            'email'=>$this->email,
            'password'=>$this->password,
            'password_confirmation'=>$this->password
        ]);
        $response->assertRedirect('/verify-otp');
        $response->assertSessionHas('temp_user');
        $response->assertSessionHas('temp_user.name',$this->username);
        $response->assertSessionHas('temp_user.email',$this->email);
        $this->assertTrue(Hash::check($this->password,session('temp_user')['password']));
    }

    /** @test */
    public function failed_registration_attempt_invalid_email(){
        $response=$this->from($this->redirectUrl)->post('/register',[
            'name'=>$this->username,
            'email'=>'emailSalah',
            'password'=>$this->password,
            'password_confirmation'=>$this->password,
        ]);
        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function failed_registration_attempt_invalid_password_confirmation(){
        $response=$this->from($this->redirectUrl)->post('/register',[
            'name'=>$this->username,
            'email'=>$this->email,
            'password'=>$this->password,
            'password_confirmation'=>'notTheSameAsPassword',
        ]);
        $response->assertSessionHasErrors('password');
    }

    /** @test */
    public function successfully_otp_attempt(){
        $response=$this->from($this->redirectUrl)->post('/register',[
            'name'=>$this->username,
            'email'=>$this->email,
            'password'=>$this->password,
            'password_confirmation'=>$this->password,
        ]);
        $otp=$this->from('/register')->post('/verify-otp',[
            'otp'=>session('temp_user')['otp'],
        ]);
        $otp->assertRedirect('/home');
        
        User::where('email',$this->email)->delete();
    }

    /** @test */
    public function failed_otp_attempt_wrong_otp(){
        $response=$this->from($this->redirectUrl)->post('/register',[
            'name'=>$this->username,
            'email'=>$this->email,
            'password'=>$this->password,
            'password_confirmation'=>$this->password,
        ]);
        $otp=$this->from('/register')->post('/verify-otp',[
            'otp'=>session('temp_user')['otp']==100000?100001:100000,
        ]);
        $otp->assertSessionHasErrors('otp');
    }




}
