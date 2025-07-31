<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Cart;
use Carbon\Carbon;
use Database\Seeders\DatabaseSeeder;

class cartTest extends TestCase
{
    use RefreshDatabase; // This will refresh the database for each test, ensuring a clean state.

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        // Create a user for authentication in tests
        $this->seed(DatabaseSeeder::class);
        $this->user = User::factory()->create();
    }

    

    /**
     * Tc1_ShowEmptyCartPage
     * Test case to ensure an empty cart page is displayed correctly when there are no cart items.
     */
    public function testTc1_ShowEmptyCartPage()
    {
        $this->actingAs($this->user)
             ->get(route('cart'))
             ->assertStatus(200)
             ->assertSee(__('cart.CartEmpty')) // Assuming 'CartEmpty' is the translation key for "Keranjang kosong"
             ->assertSee(__('cart.AddItem')); // Assuming 'AddItem' is the translation key for "Tambah item sekarang"
    }

    /**
     * Tc2_ShowCartWithUpcomingItems
     * Test case to ensure the cart page displays upcoming items correctly.
     * 
     * 
     * INI GPT salah
     */
        public function testTc2_ShowCartWithUpcomingItems()
        {
            $vehicle = Vehicle::factory()->create([
                'price' => 100000,
            ]);

            Cart::create([
                'user_id' => $this->user->id,
                'vehicle_id' => $vehicle->id,
                'start_date' => Carbon::today()->addDays(5),
                'end_date' => Carbon::today()->addDays(7),
                'subtotal' => 300000 // 3 days * 100000
            ]);

            $this->actingAs($this->user)
                ->get(route('cart'))
                ->assertStatus(200)
                ->assertSee(__('cart.RecentDate')) // Assuming 'RecentDate' is the translation key for "Tanggal Rental Mendatang"
                ->assertSee($vehicle->name); // Assuming vehicle name is displayed
        }

    /**
     * Tc3_ShowCartWithOutdatedItems
     * Test case to ensure the cart page displays outdated items correctly.
     */
    public function testTc3_ShowCartWithOutdatedItems()
    {
        $vehicle = Vehicle::factory()->create([
            'price' => 100000,
        ]);

        Cart::create([
            'user_id' => $this->user->id,
            'vehicle_id' => $vehicle->id,
            'start_date' => Carbon::today()->subDays(5),
            'end_date' => Carbon::today()->subDays(3),
            'subtotal' => 300000 // 3 days * 100000
        ]);

        $this->actingAs($this->user)
             ->get(route('cart.index'))
             ->assertStatus(200)
             ->assertSee(__('cart.PastDate')) // Assuming 'PastDate' is the translation key for "Tanggal Rental Kadaluarsa"
             ->assertSee($vehicle->name);
    }

    /**
     * Tc4_ShowCartWithMixedItems
     * Test case to ensure the cart page displays both upcoming and outdated items correctly.
     */
    public function testTc4_ShowCartWithMixedItems()
    {
        $vehicle1 = Vehicle::factory()->create(['price' => 100000]);
        $vehicle2 = Vehicle::factory()->create(['price' => 100000]);

        // Upcoming item
        Cart::create([
            'user_id' => $this->user->id,
            'vehicle_id' => $vehicle1->id,
            'start_date' => Carbon::today()->addDays(1),
            'end_date' => Carbon::today()->addDays(2),
            'subtotal' => 200000
        ]);

        // Outdated item
        Cart::create([
            'user_id' => $this->user->id,
            'vehicle_id' => $vehicle2->id,
            'start_date' => Carbon::today()->subDays(2),
            'end_date' => Carbon::today()->subDays(1),
            'subtotal' => 200000
        ]);

        $this->actingAs($this->user)
             ->get(route('cart.index'))
             ->assertStatus(200)
             ->assertSee(__('cart.RecentDate'))
             ->assertSee(__('cart.PastDate'))
             ->assertSee($vehicle1->name)
             ->assertSee($vehicle2->name);
    }

    /**
     * Tc5_AddSingleDateRangeToCartSuccessfully
     * Test case for successfully adding a single date range for a vehicle to the cart.
     */
    public function testTc5_AddSingleDateRangeToCartSuccessfully()
    {
        $vehicle = Vehicle::factory()->create([
            'price' => 100000,
        ]);

        $startDate = Carbon::today()->addDays(1)->format('Y-m-d');
        $endDate = Carbon::today()->addDays(3)->format('Y-m-d'); // 3 days rental
        $expectedSubtotal = (100000 * 3) * (1 - (0.05 * (3 - 1))); // 300000 * (1 - 0.10) = 270000

        $response = $this->actingAs($this->user)
                         ->post(route('cart.store'), [
                             'vehicle_id' => $vehicle->id,
                             'date_ranges' => [
                                 ['start_date' => $startDate, 'end_date' => $endDate]
                             ]
                         ]);

        $response->assertSessionHas('success', 'Tanggal berhasil ditambahkan ke keranjang!');
        $this->assertDatabaseHas('carts', [
            'user_id' => $this->user->id,
            'vehicle_id' => $vehicle->id,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'subtotal' => round($expectedSubtotal, 2),
        ]);
        $this->assertCount(1, Cart::all());
    }

    /**
     * Tc6_AddMultipleDateRangesToCartSuccessfully
     * Test case for successfully adding multiple date ranges for a vehicle to the cart.
     */
    public function testTc6_AddMultipleDateRangesToCartSuccessfully()
    {
        $vehicle = Vehicle::factory()->create([
            'price' => 100000,
        ]);

        $startDate1 = Carbon::today()->addDays(1)->format('Y-m-d');
        $endDate1 = Carbon::today()->addDays(2)->format('Y-m-d'); // 2 days rental
        $subtotal1 = (100000 * 2) * (1 - (0.05 * (2 - 1))); // 200000 * (1 - 0.05) = 190000

        $startDate2 = Carbon::today()->addDays(5)->format('Y-m-d');
        $endDate2 = Carbon::today()->addDays(6)->format('Y-m-d'); // 2 days rental
        $subtotal2 = (100000 * 2) * (1 - (0.05 * (2 - 1))); // 200000 * (1 - 0.05) = 190000


        $response = $this->actingAs($this->user)
                         ->post(route('cart.store'), [
                             'vehicle_id' => $vehicle->id,
                             'date_ranges' => [
                                 ['start_date' => $startDate1, 'end_date' => $endDate1],
                                 ['start_date' => $startDate2, 'end_date' => $endDate2]
                             ]
                         ]);

        $response->assertSessionHas('success', 'Tanggal berhasil ditambahkan ke keranjang!');
        $this->assertDatabaseHas('carts', [
            'user_id' => $this->user->id,
            'vehicle_id' => $vehicle->id,
            'start_date' => $startDate1,
            'end_date' => $endDate1,
            'subtotal' => round($subtotal1, 2),
        ]);
        $this->assertDatabaseHas('carts', [
            'user_id' => $this->user->id,
            'vehicle_id' => $vehicle->id,
            'start_date' => $startDate2,
            'end_date' => $endDate2,
            'subtotal' => round($subtotal2, 2),
        ]);
        $this->assertCount(2, Cart::all());
    }

    /**
     * Tc7_AddDateRangeForNonExistentVehicle
     * Test case for attempting to add a date range for a vehicle that does not exist.
     */
    public function testTc7_AddDateRangeForNonExistentVehicle()
    {
        $response = $this->actingAs($this->user)
                         ->post(route('cart.store'), [
                             'vehicle_id' => 9999, // Non-existent vehicle ID
                             'date_ranges' => [
                                 ['start_date' => Carbon::today()->addDay()->format('Y-m-d'), 'end_date' => Carbon::today()->addDays(2)->format('Y-m-d')]
                             ]
                         ]);

        $response->assertSessionHas('error', 'Kendaraan tidak ditemukan.');
        $this->assertCount(0, Cart::all());
    }

    /**
     * Tc8_AddMoreThanTenItemsToCart
     * Test case for attempting to add more than 10 items to the cart.
     */
    public function testTc8_AddMoreThanTenItemsToCart()
    {
        $vehicle = Vehicle::factory()->create([
            'price' => 100000,
        ]);

        // Add 10 items to the cart first
        for ($i = 1; $i <= 10; $i++) {
            Cart::create([
                'user_id' => $this->user->id,
                'vehicle_id' => $vehicle->id,
                'start_date' => Carbon::today()->addDays($i)->format('Y-m-d'),
                'end_date' => Carbon::today()->addDays($i + 1)->format('Y-m-d'),
                'subtotal' => 200000
            ]);
        }

        // Try to add one more
        $startDate = Carbon::today()->addDays(11)->format('Y-m-d');
        $endDate = Carbon::today()->addDays(12)->format('Y-m-d');

        $response = $this->actingAs($this->user)
                         ->post(route('cart.store'), [
                             'vehicle_id' => $vehicle->id,
                             'date_ranges' => [
                                 ['start_date' => $startDate, 'end_date' => $endDate]
                             ]
                         ]);

        $response->assertSessionHas('error', 'Maksimal 10 item pada Cart. Anda sudah memiliki 10 item.');
        $this->assertCount(10, Cart::all()); // Still 10 items in the cart
    }

    /**
     * Tc9_DeleteSpecificCartItemSuccessfully
     * Test case for successfully deleting a specific item from the cart.
     */
    public function testTc9_DeleteSpecificCartItemSuccessfully()
    {
        $vehicle = Vehicle::factory()->create();
        $cartItem = Cart::create([
            'user_id' => $this->user->id,
            'vehicle_id' => $vehicle->id,
            'start_date' => Carbon::today()->addDay(),
            'end_date' => Carbon::today()->addDays(2),
            'subtotal' => 200000
        ]);

        $this->actingAs($this->user)
             ->delete(route('cart.destroy', $cartItem->id))
             ->assertSessionHas('success', 'Item berhasil dihapus dari keranjang.');

        $this->assertDatabaseMissing('carts', ['id' => $cartItem->id]);
        $this->assertCount(0, Cart::all());
    }

    /**
     * Tc10_AttemptToDeleteAnotherUsersCartItem
     * Test case for attempting to delete a cart item that belongs to another user.
     */
    public function testTc10_AttemptToDeleteAnotherUsersCartItem()
    {
        $anotherUser = User::factory()->create();
        $vehicle = Vehicle::factory()->create();
        $cartItem = Cart::create([
            'user_id' => $anotherUser->id,
            'vehicle_id' => $vehicle->id,
            'start_date' => Carbon::today()->addDay(),
            'end_date' => Carbon::today()->addDays(2),
            'subtotal' => 200000
        ]);

        $response = $this->actingAs($this->user)
                         ->delete(route('cart.destroy', $cartItem->id));

        // The controller uses back()->withError(), which redirects back.
        // We'll assert that it redirects and the item is still in the database.
        $response->assertSessionHasErrors(); // Check for any error
        $response->assertRedirect(); // Check for redirection

        $this->assertDatabaseHas('carts', ['id' => $cartItem->id]);
    }

    /**
     * Tc11_ClearAllOutdatedItemsSuccessfully
     * Test case for successfully clearing all outdated items from the cart.
     */
    public function testTc11_ClearAllOutdatedItemsSuccessfully()
    {
        $vehicle = Vehicle::factory()->create();
        // Outdated item
        Cart::create([
            'user_id' => $this->user->id,
            'vehicle_id' => $vehicle->id,
            'start_date' => Carbon::today()->subDays(5),
            'end_date' => Carbon::today()->subDays(3),
            'subtotal' => 300000
        ]);
        // Upcoming item (should not be deleted)
        $upcomingCartItem = Cart::create([
            'user_id' => $this->user->id,
            'vehicle_id' => $vehicle->id,
            'start_date' => Carbon::today()->addDays(1),
            'end_date' => Carbon::today()->addDays(2),
            'subtotal' => 200000
        ]);

        $this->actingAs($this->user)
             ->delete(route('cart.clearOutdated'))
             ->assertSessionHas('success', 'Semua rental kedaluwarsa berhasil dihapus!');

        $this->assertDatabaseMissing('carts', ['start_date' => Carbon::today()->subDays(5)->format('Y-m-d')]);
        $this->assertDatabaseHas('carts', ['id' => $upcomingCartItem->id]);
        $this->assertCount(1, Cart::all());
    }

    /**
     * Tc12_ProcessPaymentWithSelectedItems
     * Test case for processing payment with selected cart items.
     */
    public function testTc12_ProcessPaymentWithSelectedItems()
    {
        $vehicle = Vehicle::factory()->create(['price' => 100000]);
        $cartItem1 = Cart::create([
            'user_id' => $this->user->id,
            'vehicle_id' => $vehicle->id,
            'start_date' => Carbon::today()->addDay(),
            'end_date' => Carbon::today()->addDays(2),
            'subtotal' => 190000 // 2 days * 100k - discount
        ]);
        $cartItem2 = Cart::create([
            'user_id' => $this->user->id,
            'vehicle_id' => $vehicle->id,
            'start_date' => Carbon::today()->addDays(3),
            'end_date' => Carbon::today()->addDays(4),
            'subtotal' => 190000 // 2 days * 100k - discount
        ]);

        $selectedCartIds = [$cartItem1->id, $cartItem2->id];
        $selectedCartIdsJson = json_encode($selectedCartIds);

        $response = $this->actingAs($this->user)
                         ->get(route('payment.process', ['selected_cart_ids' => $selectedCartIdsJson]));

        $response->assertStatus(200)
                 ->assertViewIs('PaymentPage')
                 ->assertViewHas('selectedCartItems', function ($items) use ($cartItem1, $cartItem2) {
                     return $items->contains($cartItem1) && $items->contains($cartItem2);
                 });
    }

    /**
     * Tc13_ProcessPaymentWithoutSelectedItems
     * Test case for attempting to process payment without any selected cart items.
     */
    public function testTc13_ProcessPaymentWithoutSelectedItems()
    {
        $response = $this->actingAs($this->user)
                         ->get(route('payment.process', ['selected_cart_ids' => '[]']));

        $response->assertRedirect()
                 ->assertSessionHas('error', 'Tidak ada item keranjang yang dipilih untuk pembayaran.');
    }

    /**
     * Tc14_ProcessPaymentWithInvalidSelectedItemsData
     * Test case for attempting to process payment with invalid selected cart items data.
     */
    public function testTc14_ProcessPaymentWithInvalidSelectedItemsData()
    {
        $response = $this->actingAs($this->user)
                         ->get(route('payment.process', ['selected_cart_ids' => 'invalid-json']));

        $response->assertRedirect()
                 ->assertSessionHas('error', 'Data keranjang yang dipilih tidak valid.');
    }

    /**
     * Tc15_ProcessPaymentWithOtherUsersCartItems
     * Test case for attempting to process payment with other users' cart items.
     */
    public function testTc15_ProcessPaymentWithOtherUsersCartItems()
    {
        $anotherUser = User::factory()->create();
        $vehicle = Vehicle::factory()->create();
        $cartItemOfAnotherUser = Cart::create([
            'user_id' => $anotherUser->id,
            'vehicle_id' => $vehicle->id,
            'start_date' => Carbon::today()->addDay(),
            'end_date' => Carbon::today()->addDays(2),
            'subtotal' => 200000
        ]);

        $selectedCartIds = [$cartItemOfAnotherUser->id];
        $selectedCartIdsJson = json_encode($selectedCartIds);

        $response = $this->actingAs($this->user)
                         ->get(route('payment.process', ['selected_cart_ids' => $selectedCartIdsJson]));

        $response->assertRedirect()
                 ->assertSessionHas('error', 'cart.WarningPayment'); // This is the exact error message from your controller
    }

    /**
     * Tc16_CartItemCountEndpointReturnsCorrectCount
     * Test case for the API endpoint that returns the cart item count.
     */
    public function testTc16_CartItemCountEndpointReturnsCorrectCount()
    {
        $vehicle = Vehicle::factory()->create();
        Cart::create([
            'user_id' => $this->user->id,
            'vehicle_id' => $vehicle->id,
            'start_date' => Carbon::today()->addDay(),
            'end_date' => Carbon::today()->addDays(2),
            'subtotal' => 200000
        ]);
        Cart::create([
            'user_id' => $this->user->id,
            'vehicle_id' => $vehicle->id,
            'start_date' => Carbon::today()->addDays(3),
            'end_date' => Carbon::today()->addDays(4),
            'subtotal' => 200000
        ]);

        $response = $this->actingAs($this->user)
                         ->get(route('cart.count')); // Assuming a route named 'cart.count'

        $response->assertStatus(200)
                 ->assertJson(['count' => 2]);
    }
}