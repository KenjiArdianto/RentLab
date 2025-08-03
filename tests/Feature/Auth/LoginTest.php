<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Mockery;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Contracts\User as ProviderUser;

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
    public function create_test_user(array $overrides=[]){
        
        return User::updateOrCreate([
            'email'=>$this->email
        ],array_merge([
            'name'=>$this->username,
            'email'=>$this->email,
            'password'=>bcrypt($this->password),
            'email_verified_at'=>now(),
        ],$overrides));
    }

    /** @test */
    public function tc001()
    {    
        $response = $this->get('/login');
        $response -> assertStatus(200);
    }

    /** @test */
    public function tc003()
    {
        $user=$this->create_test_user()->fresh();
        $response=$this->actingAs($user)->get('/login');
        $response->assertRedirect($this->redirectUrl);
    }

    /** @test */
    public function tc004()
    {
        $response=$this->from('/login')->post('/login',[]);
        $response->assertRedirect('/login');
        $response->assertSessionHasErrors(['email','password']);
        $this->assertGuest();
    }

    /** @test */
    public function tc005()
    {
        $response=$this->from('/login')->post('/login',[
            "email"=>"salahNihFormatKayaknya",
            "password"=>$this->password,
        ]);
        $response->assertRedirect('/login');
        $response->assertSessionHasErrors(['email']);
    }

    /** @test */ 
    public function tc006()
    {
        $response = $this->from('/login')->post('/login', [
            'email' => 'user@hotmail.com',
            'password' => $this->password,
        ]);
        $response->assertRedirect('/login');
        $response->assertSessionHasErrors(['email']);
    }

    /** @test */ 
    public function tc007()
    {
        $response = $this->from('/login')->post('/login', [
            'email' => $this->email,
            'password' => 'short7',
        ]);
        $response->assertRedirect('/login');
        $response->assertSessionHasErrors(['password']);
    }

     /** @test */ // TC008
    public function tc008()
    {
        $response = $this->from('/login')->post('/login', [
            'email' => $this->email,
            'password' => str_repeat('a', 26),
        ]);
        $response->assertRedirect('/login');
        $response->assertSessionHasErrors(['password']);
    }

    /** @test */ 
    public function tc009()
    {
        $response = $this->from('/login')->post('/login', [
            'email' => 'nonexist@gmail.com',
            'password' => 'somepassword',
        ]);
        $response->assertRedirect('/login');
        $response->assertSessionHasErrors(['email','password']);
        $this->assertGuest();
    }

    /** @test */ 
    public function tc010()
    {
        $this->create_test_user()->fresh();
        $response = $this->from('/login')->post('/login', [
            'email' => $this->email,
            'password' => 'wrongpass',
        ]);
        $response->assertRedirect('/login');
        $response->assertSessionHasErrors(['email'],['password']);
        $this->assertGuest();
    }

    /** @test */ 
    public function tc011()
    {
        $this->create_test_user(['suspended_at' => now()])->fresh();
       
        $response = $this->from('/login')->post('/login', [
            'email' => $this->email,
            'password' => $this->password,
        ]);
        $response->assertRedirect('/login');
        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
        $this->create_test_user(['suspended_at' => null])->fresh();
    }

    /** @test */ // TC012
    public function tc012()
    {
        $user=$this->create_test_user()->fresh();
        $response = $this->post('/login', [
            'email' => $this->email,
            'password' => $this->password,
        ]);
        $response->assertRedirect($this->redirectUrl);
        $this->assertAuthenticated();
        $this->assertAuthenticatedAs($user);
    }

    /** @test */ // TC013
    public function tc013()
    {
        $user=$this->create_test_user()->fresh();
        $response = $this->post('/login', [
            'email' => $this->email,
            'password' => $this->password,
            'remember' => 'on',
        ]);
        $response->assertRedirect($this->redirectUrl);
        $this->assertAuthenticated();
        $this->assertAuthenticatedAs($user);
        $this->assertNotNull($user->fresh()->remember_token);
    }

    /** @test */
    public function tc014()
    {
        $response = $this->get('/auth/google');
        $response->assertRedirect(); // Google redirect URL
        $this->assertStringContainsString('https://accounts.google.com', $response->headers->get('Location'));
    }

    /** @test */
    public function tc015()
    {
        $this->create_test_user(); // Ensure user exists in DB

        $googleUser = Mockery::mock(ProviderUser::class);
        $googleUser->shouldReceive('getEmail')->andReturn($this->email);
        $googleUser->shouldReceive('getName')->andReturn($this->username);

        Socialite::shouldReceive('driver->stateless->user')->andReturn($googleUser);

        $response = $this->get('/auth/google/callback');
        $response->assertRedirect('/home');

        $user = User::where('email', $this->email)->first();
        $this->assertNotNull($user);
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function tc016()
    {
        $newEmail = 'google_user_' . uniqid() . '@gmail.com';

        $googleUser = Mockery::mock(ProviderUser::class);
        $googleUser->shouldReceive('getEmail')->andReturn($newEmail);
        $googleUser->shouldReceive('getName')->andReturn('New Google User');

        Socialite::shouldReceive('driver->stateless->user')->andReturn($googleUser);

        $response = $this->get('/auth/google/callback');
        $response->assertRedirect('/home');

        $this->assertDatabaseHas('users', ['email' => $newEmail]);
        $this->assertAuthenticated();
        $this->assertAuthenticatedAs(Auth::user());
    }

    /** @test */
    public function tc017()
    {
        Socialite::shouldReceive('driver->stateless->user')
            ->andThrow(new \Exception('User cancelled login'));

        $response = $this->get('/auth/google/callback');

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('google');
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

    /** @test */    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
