<?php

namespace Tests\Feature\auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class verifyOtpTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;
    public function create_test_user(array $overrides=[]){
        
        return User::updateOrCreate([
            'email'=>'testOtp@gmail.com'
        ],array_merge([
            'name'=>'testOtp',
            'email'=>'testOtp@gmail.com',
            'password'=>Hash::make('password123'),
            'email_verified_at'=>now(),
        ],$overrides));
    }

    /** @test */
    public function delete_test_user(){
        $user = User::where('email',"=",'testOtp@gmail.com')->first();
        if($user){
            $user->delete();
            $this->assertSoftDeleted('users',['email'=>'testOtp@gmail.com']);
        }else{
            echo "User doesn't exist to delete";
            $this->assertTrue(true,"User doesn't exist to delete");
        }
    }   

    

    private $tempUserData;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tempUserData = [
            'name' => 'testOtp',
            'email' => 'testOtp@gmail.com',
            'password' => Hash::make('password123'),
            'otp' => '123456',
            'otp_expires_at' => now()->addMinutes(5),
        ];
    }
    
    
    /** @test */
    public function tc001_access_otp_page_without_registration()
    {
        $response = $this->get('/verify-otp');
        $response->assertRedirect('/register');
    }

     /** @test */
    public function tc002_access_otp_page_after_successful_registration()
    {
        $response = $this
            ->withSession(['temp_user' => $this->tempUserData,'otp_expires_at'=>now()->addMinutes(5)])
            ->get('/verify-otp');
        

        $response->assertStatus(200);
        $response->assertSee('Enter OTP'); // optional: check OTP form contents
    }

    /** @test */
    public function tc003_access_otp_page_as_logged_in_user()
    {
        $user = $this->create_test_user();
        $response = $this->actingAs($user)->from('/home')->get('/verify-otp');
        $response->assertRedirect('/home');
    }
    /** @test */
    public function tc004_submit_empty_otp_field()
    {
        $response = $this
            ->from('/verify-otp')
            ->withSession(['temp_user' => $this->tempUserData,
            'otp_expires_at'=>now()->addMinutes(5)])
            ->post('/verify-otp', ['otp' => '']);

        $response->assertRedirect('/verify-otp');
        $response->assertSessionHasErrors(['otp']);
    }

    /** @test */
    public function tc005_submit_invalid_otp_format()
    {
        $response = $this
            ->from('/verify-otp')
            ->withSession(['temp_user' => $this->tempUserData,
            'otp_expires_at'=>now()->addMinutes(5)])
            ->post('/verify-otp', ['otp' => '12abc']);

        $response->assertRedirect('/verify-otp');
        $response->assertSessionHasErrors(['otp']);
    }

    /** @test */
    public function tc006_submit_expired_otp()
    {
        $expiredData = $this->tempUserData;
        $expiredData['otp_expires_at'] = now()->subMinutes(1);

        $response = $this
            ->from('/verify-otp')
            ->withSession(['temp_user' => $expiredData,
            'otp_expires_at'=>now()->subMinutes(1)])
            ->post('/verify-otp', ['otp' => '123456']);

        $response->assertRedirect('/verify-otp');
        $response->assertSessionHasErrors('otp','OTP expired. Please resend OTP or register again.');
    }

     /** @test */
    public function tc007_submit_incorrect_otp()
    {
        $response = $this
            ->from('/verify-otp')
            ->withSession(['temp_user' => $this->tempUserData,
            'otp_expires_at'=>now()->addMinutes(5)])
            ->post('/verify-otp', ['otp' => '654321']);

        $response->assertRedirect('/verify-otp');
        $response->assertSessionHasErrors('otp', 'Invalid OTP.');
    }

    /** @test */
    public function tc008_submit_correct_otp()
    {
        $this->delete_test_user();
        $response = $this
            ->withSession(['temp_user' => $this->tempUserData,
            'otp_expires_at'=>now()->addMinutes(5)])
            ->post('/verify-otp', ['otp' => '123456']);

        $this->assertDatabaseHas('users', ['email' => $this->tempUserData['email']]);
        $this->assertAuthenticated();
        $this->assertAuthenticatedAs(User::where('email',$this->tempUserData['email'])->first());
        $response->assertRedirect('/home');
    }

    /** @test */
    public function tc009_use_resend_otp()
    {
        $this->delete_test_user();

        // resent otp within 1 minute will return error
        $response=$this->from('/verify-otp')
        ->withSession(['temp_user' => $this->tempUserData,
        'otp_expires_at'=>now()->addMinutes(5)])
        ->get('resent-verify-otp');
        $response->assertRedirect('/verify-otp');
        $response->assertSessionHasErrors(['otp' => 'Please wait before resending more OTP !']);

        // resent otp within 1 minute will return error
        $response=$this->from('/verify-otp')
        ->withSession(['temp_user' => $this->tempUserData,
        'otp_expires_at'=>now()->addMinutes(3)])
        ->get('resent-verify-otp');
        $response->assertRedirect('/verify-otp');
        $response->assertSessionHas('success', 'OTP has been resent');
        
        //check if otp differs (new otp is generated)
        $this->assertNotEquals($this->tempUserData['email'], session('temp_user')['otp']);
        $this->assertEquals(6, strlen(session('temp_user')['otp']));
    }
}
