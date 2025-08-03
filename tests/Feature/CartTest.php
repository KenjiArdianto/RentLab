<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Cart;
use App\Models\UserDetail;
use App\Models\VehicleType;
use App\Models\VehicleName;
use App\Models\VehicleCategory;
use App\Models\VehicleTransmission;
use Carbon\Carbon;
use App\Models\Location;

class CartTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $userDetail;

    protected function setUp(): void
    {
        parent::setUp();

        
        // VehicleCategory::factory()->count(2)->create();
        // VehicleTransmission::factory()->count(2)->create();

        // VehicleType::factory()->create(['id' => 1, 'name' => 'Car']);
        // VehicleType::factory()->create(['id' => 2, 'name' => 'Motorcycle']);

        // VehicleName::factory()->count(20)->create();

        VehicleType::factory()->create(['id' => 1, 'type' => 'Car']);
        VehicleType::factory()->create(['id' => 2, 'type' => 'Motorcycle']);

        VehicleName::create(['id' => 1, 'name' => 'Car Model A']);
        VehicleName::create(['id' => 2, 'name' => 'Motorcycle Model A']);


        VehicleCategory::create(['id' => 1, 'category' => 'SUV']);
        VehicleTransmission::create(['id' => 1, 'transmission' => 'Automatic']);
        Location::create(['id' => 1, 'location' => 'Jakarta']);


        $this->user = User::factory()->create([
            'name' => 'Tralalero Tralala',
            'email' => 'tralalero@example.com',
            'password' => bcrypt('password'),
            'role' => 'user', 
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
    }

    public function test_tc1_user_acces_cart_page()
    {
        
        $this->actingAs($this->user);
        $response = $this->get(route('cart'));
        $response->assertStatus(200);
        $response->assertViewIs('CartPage');
    }
    

    public function test_tc2_unauthenticated_user_redirected()
    {
        $this->assertGuest();
        $response = $this->get(route('cart'));

        // Assert that the user is redirected to the login page
        $response->assertStatus(403);
    }

    public function test_tc3_DisplayOrderedUpcommingCart()
    {
        $this->actingAs($this->user);

        $vehicle1 = Vehicle::factory()->create();
        $vehicle2 = Vehicle::factory()->create();
        
        $today = Carbon::today();
        $tomorrow = $today->copy()->addDay();
        $inTwoDays = $today->copy()->addDays(2);
        $yesterday = $today->copy()->subDay();

        Cart::create([
            'user_id' => $this->user->id,
            'vehicle_id' => $vehicle1->id,
            'start_date' => $tomorrow,
            'end_date' => $tomorrow->copy()->addDay(),
            'subtotal' => 100000,
        ]);

        Cart::create([
            'user_id' => $this->user->id,
            'vehicle_id' => $vehicle2->id,
            'start_date' => $inTwoDays,
            'end_date' => $inTwoDays->copy()->addDay(),
            'subtotal' => 200000,
        ]);

        $cartItemToday = Cart::create([
            'user_id' => $this->user->id,
            'vehicle_id' => $vehicle1->id,
            'start_date' => $today,
            'end_date' => $today->copy()->addDay(),
            'subtotal' => 50000,
        ]);

        Cart::create([
            'user_id' => $this->user->id,
            'vehicle_id' => $vehicle2->id,
            'start_date' => $yesterday,
            'end_date' => $yesterday->copy()->addDay(),
            'subtotal' => 30000,
        ]);

        $response = $this->get(route('cart'));
        $response->assertStatus(200);
        
        $upcomingCart = $response->viewData('upcomingCart');
        $this->assertCount(3, $upcomingCart);
  
        $this->assertEquals($cartItemToday->id, $upcomingCart[0]->id);
        $this->assertEquals(Cart::where('start_date', $tomorrow)->first()->id, $upcomingCart[1]->id);
        $this->assertEquals(Cart::where('start_date', $inTwoDays)->first()->id, $upcomingCart[2]->id);
    }


    public function test_tc4_displays_empty_cart_message_when_no_upcoming_items()
    {   
        $this->actingAs($this->user);
        $response = $this->get(route('cart'));
        $response->assertStatus(200);
        $response->assertViewIs('CartPage');
        $response->assertSee('Your Cart is empty.');
        $upcomingCart = $response->viewData('upcomingCart');
        $this->assertCount(0, $upcomingCart);
    }

    public function test_tc5_cart_displays_outdated_items()
    {
        // 1. User needs User ID (and is logged in)
        $this->actingAs($this->user);

        // Create a vehicle

        $vehicleName = VehicleName::create(['name' => 'Outdated Car Model']);

        // 2. Buat vehicle dengan relasi yang benar ke VehicleName
        // Menggunakan factory, tetapi menimpa relasi vehicle_name_id dengan ID yang valid
        $vehicle = Vehicle::factory()->create([
            'vehicle_name_id' => $vehicleName->id,
            'vehicle_type_id' => 1, // Pastikan ID ini ada di tabel vehicle_types
            'vehicle_transmission_id' => 1, // Pastikan ID ini ada
            'location_id' => 1, // Pastikan ID ini ada
        ]);

        // 2. Create outdated cart items where the date is smaller than today
        $yesterday = Carbon::yesterday();
        $twoDaysAgo = Carbon::today()->subDays(2);
        
        $outdatedCartItem1 = Cart::create([
            'user_id' => $this->user->id,
            'vehicle_id' => $vehicle->id,
            'start_date' => $yesterday,
            'end_date' => $yesterday,
            'subtotal' => 50000,
        ]);
        
        $outdatedCartItem2 = Cart::create([
            'user_id' => $this->user->id,
            'vehicle_id' => $vehicle->id,
            'start_date' => $twoDaysAgo,
            'end_date' => $twoDaysAgo,
            'subtotal' => 50000,
        ]);

        // Access the cart page
        $response = $this->get(route('cart'));

        // Assert that the request was successful
        $response->assertStatus(200);

        // Assert that the 'CartPage' view is returned
        $response->assertViewIs('CartPage');
        
        // Assert that outdated cart items are displayed
        $response->assertSee($outdatedCartItem1->vehicle->vehicle_name->name);
        $response->assertSee($outdatedCartItem2->vehicle->vehicle_name->name);
        
        // Assert that the outdatedCart variable in the view contains the created items
        $outdatedCart = $response->viewData('outdatedCart');
        $this->assertCount(2, $outdatedCart);
        $this->assertEquals($outdatedCartItem1->id, $outdatedCart[0]->id);
        $this->assertEquals($outdatedCartItem2->id, $outdatedCart[1]->id);
    }

    public function test_tc6_does_not_display_outdated_items_when_none_exist()
    { //karna name

        $this->actingAs($this->user);

        $vehicle = Vehicle::factory()->create();

        Cart::create([
            'user_id' => $this->user->id,
            'vehicle_id' => $vehicle->id,
            'start_date' => Carbon::tomorrow(),
            'end_date' => Carbon::tomorrow()->addDay(),
            'subtotal' => 100000,
        ]);

        $response = $this->get(route('cart'));
        $response->assertStatus(200);
        $response->assertViewIs('CartPage');
        $response->assertDontSeeText('Outdated');
        $outdatedCart = $response->viewData('outdatedCart');
        $this->assertCount(0, $outdatedCart);
    }

    public function test_tc7_subtotal_is_calculated_and_stored_correctly()
    {
        
        $this->actingAs($this->user);

        $vehicle = Vehicle::factory()->create(['price' => 100000]); // 100.000 per day

        $startDate = Carbon::today()->addDays(2);
        $endDate = Carbon::today()->addDays(5);
        $numberOfDays = $startDate->diffInDays($endDate) + 1;
        $discountPercentage = min(0.05 * ($numberOfDays - 1), 0.30);
        $expectedSubtotal = ($vehicle->price * $numberOfDays) * (1 - $discountPercentage);

        $response = $this->post(route('cart.store'), [
            'vehicle_id' => $vehicle->id,
            'date_ranges' => [
                ['start_date' => $startDate->toDateString(), 'end_date' => $endDate->toDateString()]
            ]
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Tanggal berhasil ditambahkan ke keranjang!');

        $this->assertDatabaseHas('carts', [
            'user_id' => $this->user->id,
            'vehicle_id' => $vehicle->id,
            'start_date' => $startDate->toDateString(),
            'end_date' => $endDate->toDateString(),
            'subtotal' => round($expectedSubtotal, 2),
        ]);

        $response = $this->get(route('cart'));
        $response->assertSee($expectedSubtotal);

    }

    public function test_tc8_user_successfully_deletes_item_from_cart()
    {
        // 1. Authenticate user
        $this->actingAs($this->user);

        // Create a vehicle and a cart item to be deleted
        $vehicle = Vehicle::factory()->create();
        $cartItem = Cart::create([
            'user_id' => $this->user->id,
            'vehicle_id' => $vehicle->id,
            'start_date' => Carbon::tomorrow(),
            'end_date' => Carbon::tomorrow()->addDay(),
            'subtotal' => 100000,
        ]);

        // Assert that the item exists in the database initially
        $this->assertDatabaseHas('carts', [
            'id' => $cartItem->id,
            'user_id' => $this->user->id,
        ]);
        
        // Assert that the item is displayed on the cart page before deletion
        // $response = $this->get(route('cart'));
        // $response->assertSeeText($vehicle->vehicleName->name);

        // 2. User clicks delete button on cart item (simulated with a DELETE request)
        $response = $this->delete(route('cart.destroy', $cartItem->id));
        
        // Assert that the request was successful and redirected back
        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Item berhasil dihapus dari keranjang.');

        // 3. Cart deleted from database
        $this->assertDatabaseMissing('carts', [
            'id' => $cartItem->id,
        ]);

        // 4. Cart not displayed in cart page
        // $response = $this->get(route('cart'));
        // $response->assertDontSeeText($vehicle->vehicleName->name); //??
    }

    public function test_tc9_user_cannot_delete_cart_item_not_belonging_to_him()
    {
        // Authenticate as User A
        $userA = $this->user;
        $this->actingAs($userA);

        // Create a new user (User B)
        $userB = User::factory()->create();

        // Create a vehicle and a cart item for User B
        $vehicle = Vehicle::factory()->create();
        $cartItemUserB = Cart::create([
            'user_id' => $userB->id,
            'vehicle_id' => $vehicle->id,
            'start_date' => Carbon::tomorrow(),
            'end_date' => Carbon::tomorrow()->addDay(),
            'subtotal' => 100000,
        ]);

        // Assert that the item exists in the database
        $this->assertDatabaseHas('carts', ['id' => $cartItemUserB->id]);

        // User A attempts to delete User B's cart item
        $response = $this->delete(route('cart.destroy', $cartItemUserB->id));

        // Assert that an error message is displayed
        $response->assertStatus(302); // Assumes redirect back with error
        $response->assertSessionHas('error'); // Periksa apakah ada pesan error di session

        // Assert that the cart item was NOT deleted from the database
        $this->assertDatabaseHas('carts', ['id' => $cartItemUserB->id]);
    }

    public function test_tc10_user_deletes_all_expired_cart_items()
    {   
        // Authenticate as the user
        $this->actingAs($this->user);

        // Create a vehicle
        $vehicle = Vehicle::factory()->create();

        // Create an outdated cart item
        $outdatedCartItem = Cart::create([
            'user_id' => $this->user->id,
            'vehicle_id' => $vehicle->id,
            'start_date' => Carbon::yesterday(),
            'end_date' => Carbon::yesterday(),
            'subtotal' => 50000,
        ]);

        // Create an upcoming cart item (to ensure it is not deleted)
        $upcomingCartItem = Cart::create([
            'user_id' => $this->user->id,
            'vehicle_id' => $vehicle->id,
            'start_date' => Carbon::tomorrow(),
            'end_date' => Carbon::tomorrow()->addDay(),
            'subtotal' => 100000,
        ]);

        // Assert that the outdated item exists in the database
        $this->assertDatabaseHas('carts', ['id' => $outdatedCartItem->id]);

        // User clicks 'delete all expired items' button (simulated with a DELETE request)
        $response = $this->delete(route('cart.clearOutdated'));

        // Assert that the request was successful and redirected back with a success message
        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Semua rental kedaluwarsa berhasil dihapus!');

        // All outdated cart items deleted from database
        $this->assertDatabaseMissing('carts', ['id' => $outdatedCartItem->id]);

        // The upcoming cart item should still exist
        $this->assertDatabaseHas('carts', ['id' => $upcomingCartItem->id]);

    }

    // public function test_tc11_user_directs_to_payment_page_with_selected_items()
    // { //ini name jga
    //     $this->actingAs($this->user);

    //     $vehicle1 = Vehicle::factory()->create(['price' => 100000]);
    //     $vehicle2 = Vehicle::factory()->create(['price' => 200000]);

    //     $cartItem1 = Cart::create([
    //         'user_id' => $this->user->id,
    //         'vehicle_id' => $vehicle1->id,
    //         'start_date' => Carbon::tomorrow(),
    //         'end_date' => Carbon::tomorrow()->addDay(),
    //         'subtotal' => 200000,
    //     ]);

    //     $cartItem2 = Cart::create([
    //         'user_id' => $this->user->id,
    //         'vehicle_id' => $vehicle2->id,
    //         'start_date' => Carbon::tomorrow()->addDays(2),
    //         'end_date' => Carbon::tomorrow()->addDays(3),
    //         'subtotal' => 400000,
    //     ]);

    //     $selectedCartIds = [$cartItem1->id, $cartItem2->id];
    //     $response = $this->post(route('checkout.show'), ['cart_ids' => $selectedCartIds]);

    //     $response->assertStatus(200);
    //     $response->assertViewIs('paymentPage');

    //     $this->assertDatabaseMissing('carts', ['id' => $cartItem1->id]);
    //     $this->assertDatabaseMissing('carts', ['id' => $cartItem2->id]);
        
    //     $response->assertViewHas('cartItems');
    //     $viewData = $response->viewData('cartItems');
    //     $this->assertCount(2, $viewData);
    //     $this->assertEquals($cartItem1->id, $viewData[0]->id);
    //     $this->assertEquals($cartItem2->id, $viewData[1]->id);
    // }

        public function test_tc12_user_cannot_proceed_to_payment_without_selecting_items()
        {   
            $this->actingAs($this->user);
            $response = $this->post(route('checkout.show'), ['selected_carts' => []]);

            $response->assertStatus(302);
            // $response->assertSessionHas('error', 'Please select minimum 1 cart items');
        }

    public function test_tc13_user_cannot_proceed_to_payment_with_other_users_items()
    {
        $userA = $this->user;
        $this->actingAs($userA);

        $userB = User::factory()->create();

        $vehicle = Vehicle::factory()->create(['price' => 100000]);
        $cartItemUserB = Cart::create([
            'user_id' => $userB->id,
            'vehicle_id' => $vehicle->id,
            'start_date' => Carbon::tomorrow(),
            'end_date' => Carbon::tomorrow()->addDay(),
            'subtotal' => 200000,
        ]);
        
    $selectedCartIds = [$cartItemUserB->id];
    $response = $this->post(route('checkout.show'), ['cart_ids' => $selectedCartIds]);

    $response->assertStatus(302); 
    $response->assertSessionHas('error'); 
    $this->assertDatabaseHas('carts', ['id' => $cartItemUserB->id]);
    }
}