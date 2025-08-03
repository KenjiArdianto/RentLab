<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleType;
use App\Models\VehicleName;
use App\Models\VehicleCategory;
use App\Models\VehicleTransmission;
use App\Models\UserDetail;
use App\Models\Location;
use App\Models\VehicleImage;
use App\Models\Transaction;
use App\Models\Driver;
use App\Models\UserReview;
use App\Models\Payment;
use App\Models\TransactionStatus;
use App\Models\Cart;
use Carbon\Carbon;

class DetailTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $vehicle;
    protected $admin;
    protected $userDetail;
    protected $vehicleCategory;
    protected $driver;
    protected $location;
    protected $transactionStatus;

    protected function setUp(): void
    {
        parent::setUp();
        
        $vehicleType = VehicleType::create(['id' => 1, 'type' => 'Car']);
        $vehicleName = VehicleName::create(['id' => 1, 'name' => 'Toyota Avanza']);
        $transmission = VehicleTransmission::create(['id' => 1, 'transmission' => 'Automatic']);
        $this->location = Location::create(['id' => 1, 'location' => 'Jakarta']);
        
        $this->vehicleCategory = VehicleCategory::create(['category' => 'MPV']); 
        $this->transactionStatus = TransactionStatus::create(['id' => 6, 'status' => 'Closed']);

        $this->vehicle = Vehicle::create([
            'vehicle_type_id' => $vehicleType->id,
            'vehicle_name_id' => $vehicleName->id, 
            'vehicle_transmission_id' => $transmission->id,
            'engine_cc' => 1500, 
            'seats' => 5, 
            'year' => 2020,
            'location_id' => $this->location->id, 
            'main_image' => 'https://picsum.photos/seed/test-image/700/400',
            'price' => 250000,
        ]);
        
        $this->user = User::factory()->create([
            'name' => 'Tralalero Tralala',
            'email' => 'tralalero@example.com',
            'password' => bcrypt('password'),
            'role' => 'user', 
        ]);

        $this->admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $this->userDetail = UserDetail::factory()->create([
            'user_id' => $this->user->id,
            'fname' => 'Tralalero',
            'lname' => 'Tralala',
            'phoneNumber' => '081234567890',
            'idcardNumber' => '1234567890123456',
            'dateOfBirth' => '1990-01-01',
            'idcardPicture' => 'idcard_tralalero.jpg',
        ]);

        $this->driver = Driver::factory()->create([ // Objek Driver dibuat di sini
            'name' => 'John Doe',
            'image' => 'license_johndoe.jpg',
            'location_id' => $this->location->id,
        ]);

        $this->vehicle->vehicleCategories()->attach($this->vehicleCategory->id);
    }

    /**
     * @return void
     */
    public function test_tc1_user_can_access_vehicle_detail_page()
    {
        $this->actingAs($this->user);
        $response = $this->get(route('vehicle.detail', ['id' => $this->vehicle->id]));
        $response->assertStatus(200);
        $response->assertViewIs('DetailPage');
    }

    public function test_tc2_vehicle_detail_page_displays_ratings_and_reviews()
    {
        $this->actingAs($this->user);
        
        $payment = Payment::factory()->create([
            'payment_method' => 1,
            'amount' => 100000,
            'status' => 2,
            'external_id' => 'pay_test_' . \Illuminate\Support\Str::random(10),
        ]);

        $transaction = Transaction::factory()->create([
            'user_id' => $this->user->id,
            'vehicle_id' => $this->vehicle->id,
            'driver_id' => $this->driver->id,
            'transaction_status_id' => $this->transactionStatus->id,
            'payment_id' => $payment->id,
            'price' => 100000,
            'start_book_date' => now()->subDays(5),
            'end_book_date' => now()->subDays(2),
        ]);

        $review = UserReview::factory()->create([
            'user_id' => $this->user->id,
            'transaction_id' => $transaction->id,
            'rate' => 4,
            'comment' => 'Great car, excellent service!',
            'admin_id' => $this->admin->id,
        ]);

        $response = $this->get(route('vehicle.detail', ['id' => $this->vehicle->id]));

        $response->assertStatus(200);
        $response->assertSeeText('4.0/5');
        
        $response->assertSeeText($review->comment);
    }

    public function test_tc3_vehicle_detail_page_displays_no_rating_when_no_reviews()
    {
        $this->actingAs($this->user);
        $response = $this->get(route('vehicle.detail', ['id' => $this->vehicle->id])); // Perbaikan: Gunakan $this->vehicle
        $response->assertStatus(200);
        $response->assertSeeText('- /5');
    }

    public function test_tc4_vehicle_detail_page_displays_relevant_cart_items()
    {
        
        $this->actingAs($this->user);

        $vehicle = $this->vehicle;
        $cartItem = Cart::create([
            'user_id' => $this->user->id,
            'vehicle_id' => $vehicle->id,
            'start_date' => Carbon::tomorrow(),
            'end_date' => Carbon::tomorrow()->addDay(),
            'subtotal' => 100000,
        ]);

        $response = $this->get(route('vehicle.detail', ['id' => $vehicle->id]));
        $response->assertStatus(200);
        
        $response->assertSeeText(\Carbon\Carbon::parse($cartItem->start_date)->format('j M Y'));
        $response->assertSeeText(\Carbon\Carbon::parse($cartItem->end_date)->format('j M Y'));
    }

    // 
    public function test_tc5_vehicle_detail_page_does_not_display_cart_items_when_none_exist()
    {
        $this->actingAs($this->user);
        $vehicle = $this->vehicle;
        $this->assertCount(0, Cart::where('user_id', $this->user->id)->where('vehicle_id', $vehicle->id)->get());
        $response = $this->get(route('vehicle.detail', ['id' => $vehicle->id]));
        $response->assertStatus(200);
        $response->assertSeeText(__('vehicle.DatesSelected'));
    }

    // public function test_tc6_vehicle_detail_page_redirects_unauthenticated_user_to_login()
    // {
    //     // $this->assertGuest();
        
    //     // $response = $this->get(route('vehicle.detail', ['id' => $this->vehicle->id]));

    //     // $response->assertStatus(200);
    //     // $response->assertViewIs(route('DetailPage'));
    // }

    public function test_tc7_vehicle_detail_page_displays_vehicle_images(){
        $this->actingAs($this->user);

        $images = VehicleImage::factory()->count(2)->create(['vehicle_id' => $this->vehicle->id]);
        $response = $this->get(route('vehicle.detail', ['id' => $this->vehicle->id]));
        $response->assertStatus(200);
        $response->assertSee($this->vehicle->main_image);
        foreach ($images as $image) {
            $response->assertSee($image->image);
        }
    }

    public function test_tc8_user_successfully_adds_one_item_to_cart()
    {
        $this->actingAs($this->user);

        $startDate = Carbon::today()->addDays(5);
        $endDate = Carbon::today()->addDays(6);

        $response = $this->post(route('cart.store'), [
            'vehicle_id' => $this->vehicle->id,
            'date_ranges' => [
                ['start_date' => $startDate->toDateString(), 'end_date' => $endDate->toDateString()]
            ]
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('vehicle.detail', ['id' => $this->vehicle->id]));

        $this->assertDatabaseHas('carts', [
            'user_id' => $this->user->id,
            'vehicle_id' => $this->vehicle->id,
            'start_date' => $startDate->toDateString(),
            'end_date' => $endDate->toDateString(),
        ]);
    }

    public function test_tc9_user_successfully_adds_more_than_one_item_to_cart()
    {
        $this->actingAs($this->user);
        $vehicle = $this->vehicle;

        $startDate1 = Carbon::today()->addDays(5);
        $endDate1 = Carbon::today()->addDays(6);
        $startDate2 = Carbon::today()->addDays(8);
        $endDate2 = Carbon::today()->addDays(9);

        $response = $this->post(route('cart.store'), [
            'vehicle_id' => $vehicle->id,
            'date_ranges' => [
                ['start_date' => $startDate1->toDateString(), 'end_date' => $endDate1->toDateString()],
                ['start_date' => $startDate2->toDateString(), 'end_date' => $endDate2->toDateString()]
            ]
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('vehicle.detail', ['id' => $vehicle->id]));
        $response->assertSessionHas('success', 'Tanggal berhasil ditambahkan ke keranjang!');

        $this->assertDatabaseHas('carts', [
            'user_id' => $this->user->id,
            'vehicle_id' => $vehicle->id,
            'start_date' => $startDate1->toDateString(),
            'end_date' => $endDate1->toDateString(),
        ]);

        $this->assertDatabaseHas('carts', [
            'user_id' => $this->user->id,
            'vehicle_id' => $vehicle->id,
            'start_date' => $startDate2->toDateString(),
            'end_date' => $endDate2->toDateString(),
        ]);
    }

   public function test_tc10_user_can_add_more_than_three_items_if_no_server_validation()
    {
        $this->actingAs($this->user);
        $vehicle = $this->vehicle;
        
        $response = $this->post(route('cart.store'), [
            'vehicle_id' => $vehicle->id,
            'date_ranges' => [
                ['start_date' => Carbon::today()->addDays(1)->toDateString(), 'end_date' => Carbon::today()->addDays(2)->toDateString()],
                ['start_date' => Carbon::today()->addDays(3)->toDateString(), 'end_date' => Carbon::today()->addDays(4)->toDateString()],
                ['start_date' => Carbon::today()->addDays(5)->toDateString(), 'end_date' => Carbon::today()->addDays(6)->toDateString()],
                ['start_date' => Carbon::today()->addDays(7)->toDateString(), 'end_date' => Carbon::today()->addDays(8)->toDateString()]
            ]
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('vehicle.detail', ['id' => $vehicle->id]));

        $this->assertDatabaseCount('carts', 4);
    }

    public function test_tc10_user_cannot_add_to_full_cart()
    {
        $this->actingAs($this->user);
        $vehicle = $this->vehicle;
        for ($i = 1; $i <= 10; $i++) {
            Cart::factory()->create([
                'user_id' => $this->user->id,
                'vehicle_id' => $vehicle->id,
                'start_date' => Carbon::today()->addDays($i * 2),
                'end_date' => Carbon::today()->addDays($i * 2 + 1),
            ]);
        }

        $startDate = Carbon::today()->addDays(25);
        $endDate = Carbon::today()->addDays(26);

        $response = $this->post(route('cart.store'), [
            'vehicle_id' => $vehicle->id,
            'date_ranges' => [
                ['start_date' => $startDate->toDateString(), 'end_date' => $endDate->toDateString()]
            ]
        ]);
        
        $response->assertStatus(302);
        $response->assertSessionHas('error', 'Maksimal 10 item pada Cart. Anda sudah memiliki 10 item.');
        $this->assertDatabaseMissing('carts', [
            'user_id' => $this->user->id,
            'start_date' => $startDate->toDateString(),
        ]);
    }


    public function test_tc11_user_cannot_add_item_with_empty_dates()
    {
        $this->actingAs($this->user);
        $vehicle = $this->vehicle;

        $response = $this->post(route('cart.store'), [
            'vehicle_id' => $vehicle->id,
            'date_ranges' => []
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['date_ranges']);
        
        $this->assertDatabaseCount('carts', 0);
    }



}