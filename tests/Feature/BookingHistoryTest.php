<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Cart;
use App\Models\Payment;
use App\Models\Transaction;
use App\Models\TransactionStatus;
use App\Models\UserDetail;
use App\Models\VehicleType;
use App\Models\VehicleName;
use App\Models\VehicleCategory;
use App\Models\VehicleTransmission;
use App\Models\Driver;
use Carbon\Carbon;
use App\Models\Location;

class BookingHistoryTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $userDetail;

    protected function setUp(): void
    {
        parent::setUp();

        VehicleType::factory()->create(['id' => 1, 'type' => 'Car']);
        VehicleType::factory()->create(['id' => 2, 'type' => 'Motorcycle']);

        VehicleName::create(['id' => 1, 'name' => 'Car Model A']);
        VehicleName::create(['id' => 2, 'name' => 'Motorcycle Model A']);
        VehicleName::create(['id' => 2, 'name' => 'Car Model B']);

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

    /** @test */
    public function a_user_can_login_manually_and_view_the_booking_history_page(): void
    {
        $this->actingAs($this->user);
        $response = $this->get(route('booking.history'));
        $response->assertStatus(200);
        $response->assertViewIs('booking-history');
    }

    /** @test */
    public function a_guest_is_forbidden_from_viewing_the_booking_history_page(): void
    {
        $response = $this->get(route('booking.history'));

        $response->assertForbidden();
    }

    /** @test */
    public function a_user_can_filter_transactions_by_vehicle_name(): void
    {
        $avanzaName = VehicleName::factory()->create(['name' => 'Avanza']);
        $xeniaName = VehicleName::factory()->create(['name' => 'Xenia']);
        $vehicleAvanza = Vehicle::factory()->create(['vehicle_name_id' => $avanzaName->id]);
        $vehicleXenia = Vehicle::factory()->create(['vehicle_name_id' => $xeniaName->id]);

        $statusSelesai = TransactionStatus::factory()->create(['status' => 'Closed']);
        $payment = Payment::factory()->create();
        $driver = Driver::factory()->create();

        Transaction::factory()->create([
            'user_id' => $this->user->id,
            'vehicle_id' => $vehicleAvanza->id,
            'payment_id' => $payment->id,
            'driver_id' => $driver->id,
            'transaction_status_id' => $statusSelesai->id,
            'start_book_date' => now()->subDays(5),
            'end_book_date' => now()->subDays(2),
            'price' => 100000,
        ]);

        Transaction::factory()->create([
            'user_id' => $this->user->id,
            'vehicle_id' => $vehicleXenia->id,
            'payment_id' => $payment->id,
            'driver_id' => $driver->id,
            'transaction_status_id' => $statusSelesai->id,
            'start_book_date' => now()->subDays(4),
            'end_book_date' => now()->subDays(1),
            'price' => 120000,
        ]);

        $this->actingAs($this->user);
        $response = $this->get(route('booking.history', ['history_search' => 'Avanza']));

        $response->assertStatus(200);
        $response->assertViewIs('booking-history');
        $response->assertSee('Avanza');
        $response->assertDontSee('Xenia');
    }

    /** @test */
    public function a_user_can_filter_transactions_by_date_range(): void
    {
        $statusSelesai = TransactionStatus::factory()->create(['status' => 'Closed']);
        $payment = Payment::factory()->create();
        $driver = Driver::factory()->create();

        $vehicleInDateRange = Vehicle::factory()->for(VehicleName::factory(['name' => 'Avanza Agustus']))->create();
        $vehicleOutDateRange = Vehicle::factory()->for(VehicleName::factory(['name' => 'Xenia September']))->create();

        Transaction::factory()->create([
            'user_id' => $this->user->id,
            'vehicle_id' => $vehicleInDateRange->id,
            'payment_id' => $payment->id,
            'driver_id' => $driver->id,
            'transaction_status_id' => $statusSelesai->id,
            'start_book_date' => '2025-08-10',
            'end_book_date' => '2025-08-12',
            'price' => 150000,
        ]);

        Transaction::factory()->create([
            'user_id' => $this->user->id,
            'vehicle_id' => $vehicleOutDateRange->id,
            'payment_id' => $payment->id,
            'driver_id' => $driver->id,
            'transaction_status_id' => $statusSelesai->id,
            'start_book_date' => '2025-09-05',
            'end_book_date' => '2025-09-07',
            'price' => 180000,
        ]);

        $this->actingAs($this->user);
        $response = $this->get(route('booking.history', [
            'date_from' => '2025-08-01',
            'date_to' => '2025-08-31',
        ]));

        $response->assertStatus(200);
        $response->assertViewIs('booking-history');
        $response->assertSee('Avanza Agustus');
        $response->assertDontSee('Xenia September');
    }
}