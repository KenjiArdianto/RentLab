<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Contracts\User as ProviderUser;
use Mockery;
use Illuminate\Support\Facades\Auth;

class RegisterTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    protected string $redirectUrl="/home";
    protected string $username="testRegister";
    protected string $email="testRegisterEmail@gmail.com";
    protected string $password="password123";

    use RefreshDatabase;

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

    /** @test */
    public function tc001_register_page_loads_successfully()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
        $response->assertSee('Register'); // Optional
    }

     /** @test */
    public function tc002_register_page_has_required_fields()
    {
        $response = $this->get('/register');

        $response->assertSee('name');
        $response->assertSee('email');
        $response->assertSee('password');
        $response->assertSee('password_confirmation');
        $response->assertSee('Register'); // or button text
    }

    /** @test */
    public function tc003_authenticated_user_redirected_from_register()
    {
        $user = $this->create_test_user();
        $response = $this->actingAs($user)->get('/register');
        $response->assertRedirect('/home'); // or your home route
    }

    /** @test */
    public function tc004_register_form_empty_fields_trigger_errors()
    {
        $response = $this->from('/register')->post('/register', []);
        $response->assertRedirect('/register');
        $response->assertSessionHasErrors(['name', 'email', 'password']);
    }

    /** @test */
    public function tc005_submit_short_or_long_name()
    {
        $short = $this->from('/register')->post('/register', [
            'name' => 'Jo',
            'email' => 'short@gmail.com',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ]);
        $short->assertRedirect('/register');
        $short->assertSessionHasErrors(['name']);

        $long = $this->from('/register')->post('/register', [
            'name' => 'JohndoeJohndoe123',
            'email' => 'long@gmail.com',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ]);
        $long->assertRedirect('/register');
        $long->assertSessionHasErrors(['name']);
    }
    /** @test */
    public function tc006_submit_name_with_invalid_characters()
    {
         $response = $this->from('/register')->post('/register', [
            'name' => 'John@#$',
            'email' => 'valid@gmail.com',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ]);
        $response->assertRedirect('/register');
        $response->assertSessionHasErrors(['name']);
    }

    /** @test */
    public function tc007_non_gmail_email_rejected()
    {
         $response = $this->from('/register')->post('/register', [
            'name' => 'User One',
            'email' => 'test@yahoo.com',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ]);
        $response->assertRedirect('/register');
        $response->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function tc008_email_already_registered()
    {
        $account=$this->create_test_user()->fresh();
        $response = $this->from('/register')->post('/register', [
            'name' => $account->name,
            'email' => $account->email,
            'password' => $this->password,
            'password_confirmation' => $this->password
        ]);
        $response->assertRedirect('/register');
        $response->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function tc009_passwords_do_not_match()
    {
        $response = $this->from('/register')->post('/register', [
            'name' => 'User',
            'email' => 'test@gmail.com',
            'password' => 'password123',
            'password_confirmation' => 'password124'
        ]);
        $response->assertRedirect('/register');
        $response->assertSessionHasErrors(['password']);
    }

    /** @test */
    public function tc010_password_contains_non_ascii()
    {
        $response = $this->from('/register')->post('/register', [
            'name' => 'User',
            'email' => 'ascii@gmail.com',
            'password' => 'passw€rd123',
            'password_confirmation' => 'passw€rd123'
        ]);
        $response->assertRedirect('/register');
        $response->assertSessionHasErrors(['password']);
    }

    /** @test */
    public function tc011_successful_registration()
    {
        $this->delete_test_user();
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
    public function tc012_guest_is_redirected_to_google_auth()
    {
        $response = $this->get('/auth/google');
        $response->assertRedirect(); // Google redirect URL
        $this->assertStringContainsString('https://accounts.google.com', $response->headers->get('Location'));
    }

    /** @test */
    public function tc013_existing_user_logs_in_via_google()
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
    public function tc014_new_user_created_and_logged_in_via_google()
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
    public function tc015_google_auth_cancellation_redirects_back()
    {
        Socialite::shouldReceive('driver->stateless->user')
            ->andThrow(new \Exception('User cancelled login'));

        $response = $this->get('/auth/google/callback');

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('google');
        $this->assertGuest();
    }

    /** @test */
    public function successfully_otp_attempt(){
        $response=$this->from($this->redirectUrl)->post('/register',[
            'name'=>$this->username,
            'email'=>$this->email,
            'password'=>$this->password,
            'password_confirmation'=>$this->password,
        ]);
        // dd(session()->all());
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

    /** @test */
    public function successful_google_attempt(){
        // Fake Google user
        $googleUser=Mockery::mock(ProviderUser::class);
        $googleUser->shouldReceive('getId')->andReturn('123456789');
        $googleUser->shouldReceive('getEmail')->andReturn($this->email);
        $googleUser->shouldReceive('getName')->andReturn($this->username);

        // Fake the Socialite driver
        Socialite::shouldReceive('driver->stateless->user')->andReturn($googleUser);
         
        // Act: Simulate hitting the Google callback route
        $response = $this->get('/auth/google/callback');

        // Assert: Redirect happened, user exists, and is authenticated
        $response->assertRedirect('/home'); // Or whatever your redirect path is

        $user = User::where('email', $this->email)->first();
        $this->assertNotNull($user);
        $this->assertAuthenticatedAs($user);
        $user->delete();
    }

    /** @test */
    public function failed_google_attempt_user_missing_email()
    {
        $googleUser = Mockery::mock(ProviderUser::class);
        $googleUser->shouldReceive('getId')->andReturn('123456789');
        $googleUser->shouldReceive('getName')->andReturn($this->username);
        $googleUser->shouldReceive('getEmail')->andReturn(null); // missing email

        Socialite::shouldReceive('driver->stateless->user')->andReturn($googleUser);

        $response = $this->get('/auth/google/callback');
        $response->assertRedirect('/login');
        $response->assertSessionHas('error', 'Google account did not provide a valid email.');
    }






}
