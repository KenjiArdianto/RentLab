<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase; 
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CartTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Vehicle $vehicle;

    protected function setUp(): void
    {
        parent::setUp();
        // Create a user for authentication
        $this->user = User::factory()->create([
            'email' => 'testuser@example.com',
            'email_verified_at' => now(),
        ]);

        // Create a vehicle to add to the cart
        $this->vehicle = Vehicle::factory()->create([
            'price' => 100000,
        ]);
    }

    /**
     * Test Case 1: Add a single item to the cart.
     *
     * @return void
     */
    public function test_case_1_add_single_item_to_cart()
    {
        $this->actingAs($this->user);

        $startDate = Carbon::today()->addDays(5)->format('Y-m-d');
        $endDate = Carbon::today()->addDays(6)->format('Y-m-d');

        $response = $this->post(route('cart.store'), [
            'vehicle_id' => $this->vehicle->id,
            'date_ranges' => [
                [
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                ]
            ],
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Tanggal berhasil ditambahkan ke keranjang!');
        $this->assertCount(1, Cart::all());
        $cartItem = Cart::first();
        $this->assertEquals($this->user->id, $cartItem->user_id);
        $this->assertEquals($this->vehicle->id, $cartItem->vehicle_id);
    }

    /**
     * Test Case 2: Add multiple items to the cart.
     *
     * @return void
     */
    public function test_case_2_add_multiple_items_to_cart()
    {
        $this->actingAs($this->user);

        $dateRanges = [
            [
                'start_date' => Carbon::today()->addDays(5)->format('Y-m-d'),
                'end_date' => Carbon::today()->addDays(6)->format('Y-m-d'),
            ],
            [
                'start_date' => Carbon::today()->addDays(10)->format('Y-m-d'),
                'end_date' => Carbon::today()->addDays(12)->format('Y-m-d'),
            ],
        ];

        $response = $this->post(route('cart.store'), [
            'vehicle_id' => $this->vehicle->id,
            'date_ranges' => $dateRanges,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Tanggal berhasil ditambahkan ke keranjang!');
        $this->assertCount(2, Cart::all());
    }

    /**
     * Test Case 3: Add an item to a cart that already contains some items.
     *
     * @return void
     */
    public function test_case_3_add_item_to_existing_cart()
    {
        $this->actingAs($this->user);

        Cart::factory()->create([
            'user_id' => $this->user->id,
            'vehicle_id' => $this->vehicle->id,
            'start_date' => Carbon::today()->addDays(1),
            'end_date' => Carbon::today()->addDays(2),
        ]);

        $dateRanges = [
            [
                'start_date' => Carbon::today()->addDays(5)->format('Y-m-d'),
                'end_date' => Carbon::today()->addDays(6)->format('Y-m-d'),
            ],
        ];

        $response = $this->post(route('cart.store'), [
            'vehicle_id' => $this->vehicle->id,
            'date_ranges' => $dateRanges,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Tanggal berhasil ditambahkan ke keranjang!');
        $this->assertCount(2, Cart::all());
    }

    /**
     * Test Case 4: Add an item to a cart with the maximum number of items (10).
     *
     * @return void
     */
    public function test_case_4_add_item_to_full_cart()
    {
        $this->actingAs($this->user);

        // Create 10 existing cart items
        for ($i = 1; $i <= 10; $i++) {
            Cart::factory()->create([
                'user_id' => $this->user->id,
                'vehicle_id' => $this->vehicle->id,
                'start_date' => Carbon::today()->addDays($i * 2),
                'end_date' => Carbon::today()->addDays($i * 2 + 1),
            ]);
        }

        $dateRanges = [
            [
                'start_date' => Carbon::today()->addDays(25)->format('Y-m-d'),
                'end_date' => Carbon::today()->addDays(26)->format('Y-m-d'),
            ],
        ];

        $response = $this->post(route('cart.store'), [
            'vehicle_id' => $this->vehicle->id,
            'date_ranges' => $dateRanges,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Maksimal 10 item pada Cart. Anda sudah memiliki 10 item.');
        $this->assertCount(10, Cart::all()); // The count should still be 10
    }

    /**
     * Test Case 5: View the cart page with upcoming items.
     *
     * @return void
     */
    public function test_case_5_view_cart_with_upcoming_items()
    {
        $this->actingAs($this->user);

        // Create upcoming cart items
        Cart::factory()->create([
            'user_id' => $this->user->id,
            'start_date' => Carbon::today()->addDays(5),
            'end_date' => Carbon::today()->addDays(6),
        ]);

        $response = $this->get(route('cart'));

        $response->assertStatus(200);
        $response->assertViewIs('CartPage');
        $response->assertViewHas('upcomingCart');
        $response->assertViewHas('outdatedCart');
        $this->assertCount(1, $response->viewData('upcomingCart'));
        $this->assertCount(0, $response->viewData('outdatedCart'));
    }

    /**
     * Test Case 6: View the cart page with outdated items.
     *
     * @return void
     */
    public function test_case_6_view_cart_with_outdated_items()
    {
        $this->actingAs($this->user);

        // Create outdated cart items
        Cart::factory()->create([
            'user_id' => $this->user->id,
            'start_date' => Carbon::today()->subDays(5),
            'end_date' => Carbon::today()->subDays(4),
        ]);

        $response = $this->get(route('cart'));

        $response->assertStatus(200);
        $response->assertViewIs('CartPage');
        $response->assertViewHas('upcomingCart');
        $response->assertViewHas('outdatedCart');
        $this->assertCount(0, $response->viewData('upcomingCart'));
        $this->assertCount(1, $response->viewData('outdatedCart'));
    }

    /**
     * Test Case 7: View an empty cart page.
     *
     * @return void
     */
    public function test_case_7_view_empty_cart()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('cart'));

        $response->assertStatus(200);
        $response->assertViewIs('CartPage');
        $response->assertViewHas('upcomingCart');
        $response->assertViewHas('outdatedCart');
        $this->assertCount(0, $response->viewData('upcomingCart'));
        $this->assertCount(0, $response->viewData('outdatedCart'));
        $response->assertSeeText('Your cart is empty.'); // Assumes this text exists in the view
    }

    /**
     * Test Case 8: Delete a single item from the cart.
     *
     * @return void
     */
    public function test_case_8_delete_single_item_from_cart()
    {
        $this->actingAs($this->user);
        $cartItem = Cart::factory()->create(['user_id' => $this->user->id]);
        $this->assertCount(1, Cart::all());

        $response = $this->delete(route('cart.destroy', $cartItem->id));

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Item berhasil dihapus dari keranjang.');
        $this->assertCount(0, Cart::all());
    }

    /**
     * Test Case 9: Delete an item from the cart that does not belong to the user.
     *
     * @return void
     */
    public function test_case_9_cannot_delete_other_users_cart_item()
    {
        $otherUser = User::factory()->create();
        $this->actingAs($this->user);

        $otherUsersCartItem = Cart::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->delete(route('cart.destroy', $otherUsersCartItem->id));

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertCount(1, Cart::all());
    }

    /**
     * Test Case 10: Clear all outdated items from the cart.
     *
     * @return void
     */
    public function test_case_10_clear_all_outdated_items()
    {
        $this->actingAs($this->user);

        // Create outdated items
        Cart::factory()->create([
            'user_id' => $this->user->id,
            'start_date' => Carbon::today()->subDays(10),
            'end_date' => Carbon::today()->subDays(8),
        ]);
        Cart::factory()->create([
            'user_id' => $this->user->id,
            'start_date' => Carbon::today()->subDays(5),
            'end_date' => Carbon::today()->subDays(4),
        ]);

        // Create an upcoming item that should not be deleted
        Cart::factory()->create([
            'user_id' => $this->user->id,
            'start_date' => Carbon::today()->addDays(2),
            'end_date' => Carbon::today()->addDays(3),
        ]);

        $this->assertCount(3, Cart::all());

        $response = $this->delete(route('cart.clearOutdated'));

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Semua rental kedaluwarsa berhasil dihapus!');
        $this->assertCount(1, Cart::all()); // Only the upcoming item should remain
        $this->assertEquals(Carbon::today()->addDays(2)->format('Y-m-d'), Cart::first()->start_date);
    }
}