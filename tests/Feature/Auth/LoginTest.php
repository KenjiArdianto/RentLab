<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class LoginTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    protected string $email = "test001@gmail.com";
    protected string $password = "password123";
    protected string $username = "John Doe";
    protected string $redirectUrl="/home";

    /** @test */
    public function get_page(){
        $response = $this->get('/login');
        $response -> assertStatus(200);
    }

    /** @test */
    public function create_test_user(){
        $user = User::factory()->create([
            'name'=>$this->username,
            'email'=>$this->email,
            'password'=>bcrypt($this->password),
            'email_verified_at'=>now(),
        ]);
        $this->assertDatabaseHas('users', [
            'email' => $this->email,
        ]);
    }

    /** @test */
    public function successful_login_attempt(){
        $response = $this->post('/login',[
            'email'=>$this->email,
            'password'=>$this->password,
        ]);
        $response->assertRedirect($this->redirectUrl);

        $user=User::where('email',$this->email)->first();

        $this->assertAuthenticated();
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function failed_login_attempt_wrong_password(){
        $response = $this->from('/login')
        ->post('/login',[
            'email'=>$this->email,
            'password'=>'wrongPassword',
        ]);
        $response->assertRedirect('/login');
        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    /** @test */
    public function failed_login_attempt_invalid_email_format(){
        $response = $this->from('login')
        ->post('/login',[
            'email'=>'wrongEmailFormat',
            'password'=>$this->password,
        ]);
        $response->assertRedirect('/login');
        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }
    
    /** @test */
    public function delete_test_user(){
        $user = User::where('email',"=",$this->email)->first();
        if($user){
            $user->delete();
            $this->assertSoftDeleted('users',['email'=>$this->email]);
        }else{
            echo "User doesn't exist to delete";
            $this->assertTrue(true,"User doesn't exist to delete");
        }
    }   
}
