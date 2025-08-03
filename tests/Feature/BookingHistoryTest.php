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
    public function tc1_a_user_can_login_manually_and_view_the_booking_history_page(): void
    {
        $this->actingAs($this->user);
        $response = $this->get(route('booking.history'));
        $response->assertStatus(200);
        $response->assertViewIs('booking-history');
    }

    /** @test */
    public function tc2_a_guest_is_forbidden_from_viewing_the_booking_history_page(): void
    {
        $response = $this->get(route('booking.history'));

        $response->assertForbidden();
    }

    /** @test */
    public function tc3_a_user_can_filter_transactions_by_vehicle_name(): void
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
    public function tc4_a_user_can_filter_transactions_by_date_range(): void
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

    /** @test */
    public function tc5_it_displays_a_message_when_search_yields_no_results(): void
    {
        $statusSelesai = TransactionStatus::factory()->create(['status' => 'Closed']);
        $payment = Payment::factory()->create(['status' => 'PAID']);
        $driver = Driver::factory()->create(['name' => 'Budi Santoso']);
        $vehicle = Vehicle::factory()->for(VehicleName::factory(['name' => 'Pajero Detail']))->create();

        Transaction::factory()->create([
            'user_id' => $this->user->id,
            'vehicle_id' => $vehicle->id,
            'payment_id' => $payment->id,
            'driver_id' => $driver->id,
            'driver_fee' => 50000,
            'price' => 730000,
            'transaction_status_id' => $statusSelesai->id,
            'start_book_date' => now(),
            'end_book_date' => now()->addDays(2),
        ]);

        $this->actingAs($this->user);
        $response = $this->get(route('booking.history', ['history_search' => 'Lamborghini']));

        $response->assertStatus(200);
        $response->assertDontSee('Pajero Detail');
        $response->assertSee(__('booking-history.modal.noresulttransaction'));
    }
/** @test */
    public function tc6_a_user_can_successfully_submit_a_review_for_a_completed_transaction(): void
    {
        $statusCompleted = TransactionStatus::factory()->create(['id' => 5, 'status' => 'Completed']);
        $vehicle = Vehicle::factory()->create();
        $payment = Payment::factory()->create();
        $driver = Driver::factory()->create();

        $transactionToReview = Transaction::factory()->create([
            'user_id' => $this->user->id,
            'vehicle_id' => $vehicle->id,
            'transaction_status_id' => $statusCompleted->id,
            'payment_id' => $payment->id,
            'driver_id' => $driver->id,
            'price' => 250000,
            'driver_fee' => 50000,
        ]);

        $reviewData = [
            'rating' => 5,
            'comment' => 'Mobilnya bagus dan bersih!',
        ];

        $this->actingAs($this->user);
        $response = $this->post(route('reviews.store', $transactionToReview), $reviewData);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('vehicle_reviews', [
            'transaction_id' => $transactionToReview->id,
            'user_id' => $this->user->id,
            'vehicle_id' => $vehicle->id,
            'rate' => $reviewData['rating'],
            'comment' => $reviewData['comment'],
        ]);
    }

    /** @test */
    public function tc7_it_fails_review_submission_if_rating_is_missing(): void
    {
        $statusCompleted = TransactionStatus::factory()->create(['id' => 5, 'status' => 'Completed']);
        $vehicle = Vehicle::factory()->create();
        $payment = Payment::factory()->create();
        $driver = Driver::factory()->create();

        $transactionToReview = Transaction::factory()->create([
            'user_id' => $this->user->id,
            'vehicle_id' => $vehicle->id,
            'transaction_status_id' => $statusCompleted->id,
            'payment_id' => $payment->id,
            'driver_id' => $driver->id,
            'price' => 250000,
            'driver_fee' => 50000,
        ]);

        $invalidReviewData = [
            'comment' => 'Komentar ada, tapi rating tidak ada.',
        ];

        $this->actingAs($this->user);
        $response = $this->post(route('reviews.store', $transactionToReview), $invalidReviewData);

        $response->assertSessionHasErrors('rating');

        $this->assertDatabaseMissing('vehicle_reviews', [
            'transaction_id' => $transactionToReview->id,
        ]);
    }

    /** @test */
    public function tc8_it_prevents_a_user_from_reviewing_the_same_transaction_twice(): void
    {
        $statusCompleted = TransactionStatus::factory()->create(['id' => 5, 'status' => 'Completed']);
        $vehicle = Vehicle::factory()->create();
        $payment = Payment::factory()->create();
        $driver = Driver::factory()->create();

        $reviewedTransaction = Transaction::factory()->create([
            'user_id' => $this->user->id,
            'vehicle_id' => $vehicle->id,
            'transaction_status_id' => $statusCompleted->id,
            'payment_id' => $payment->id,
            'driver_id' => $driver->id,
            'price' => 250000,
            'driver_fee' => 50000,
        ]);

        \App\Models\VehicleReview::factory()->create([
            'transaction_id' => $reviewedTransaction->id,
            'user_id' => $this->user->id,
            'vehicle_id' => $vehicle->id,
            'rate' => 4,
            'comment' => 'Ini adalah review yang sudah ada.',
        ]);

        $this->actingAs($this->user);
        $response = $this->get(route('booking.history'));

        $response->assertStatus(200);

        $response->assertSee('Ini adalah review yang sudah ada.');
        
        $reviewSubmitUrl = route('reviews.store', $reviewedTransaction);
        $response->assertDontSee($reviewSubmitUrl);
    }
/** @test */
    public function tc9_a_user_can_successfully_cancel_an_ongoing_order(): void
    {
        $statusOnBooking = TransactionStatus::factory()->create(['id' => 2, 'status' => 'On Booking']);
        $statusCanceled = TransactionStatus::factory()->create(['id' => 7, 'status' => 'Canceled']);
        
        $payment = Payment::factory()->create();
        $driver = Driver::factory()->create();
        $vehicle = Vehicle::factory()->create();

        $transactionToCancel = Transaction::factory()->create([
            'user_id' => $this->user->id,
            'transaction_status_id' => $statusOnBooking->id,
            'payment_id' => $payment->id,
            'driver_id' => $driver->id,
            'vehicle_id' => $vehicle->id,
            'price' => 200000,
            'driver_fee' => 50000,
        ]);

        $this->actingAs($this->user);
        $response = $this->post(route('booking.cancel', $transactionToCancel));

        $response->assertRedirect(route('booking.history', ['active_tab' => 'history']));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('transactions', [
            'id' => $transactionToCancel->id,
            'transaction_status_id' => $statusCanceled->id,
        ]);
    }

    /** @test */
    public function tc10_it_prevents_a_user_from_canceling_a_completed_order(): void
    {
        $statusCompleted = TransactionStatus::factory()->create(['id' => 5, 'status' => 'Completed']);
        
        $payment = Payment::factory()->create();
        $driver = Driver::factory()->create();
        $vehicle = Vehicle::factory()->create();

        $completedTransaction = Transaction::factory()->create([
            'user_id' => $this->user->id,
            'transaction_status_id' => $statusCompleted->id,
            'payment_id' => $payment->id,
            'driver_id' => $driver->id,
            'vehicle_id' => $vehicle->id,
            'price' => 200000,
            'driver_fee' => 50000,
        ]);

        $this->actingAs($this->user);
        $response = $this->get(route('booking.history'));

        $response->assertStatus(200);

        $cancelUrl = route('booking.cancel', $completedTransaction);
        $response->assertDontSee($cancelUrl);
    }

    /** @test */
    /** @test */
    public function tc11_it_automatically_expires_an_order_when_payment_timer_runs_out(): void
    {
        $statusOnPayment = TransactionStatus::factory()->create(['id' => 1, 'status' => 'On Payment']);
        $statusCanceled = TransactionStatus::factory()->create(['id' => 7, 'status' => 'Canceled']);

        $payment = Payment::factory()->create(['status' => 'PENDING']);
        $driver = Driver::factory()->create();
        $vehicle = Vehicle::factory()->create();

        $transactionToExpire = Transaction::factory()->create([
            'user_id' => $this->user->id,
            'payment_id' => $payment->id,
            'transaction_status_id' => $statusOnPayment->id,
            'driver_id' => $driver->id,
            'vehicle_id' => $vehicle->id,
            'price' => 200000,
            'driver_fee' => 50000,
        ]);

        $this->actingAs($this->user);
        $response = $this->post(route('booking.expire', $transactionToExpire));

        $response->assertOk();
        $response->assertJson(['success' => true]);

        $transactionToExpire->refresh();
        $payment->refresh();
        
        $this->assertEquals($statusCanceled->id, $transactionToExpire->transaction_status_id);
        $this->assertEquals('EXPIRED', $payment->status);
    }

    /** @test */
    public function tc12_a_user_can_download_a_pdf_receipt_for_a_paid_transaction(): void
    {
        $payment = Payment::factory()->create(['status' => 'PAID']);
        $driver = Driver::factory()->create();
        $vehicle = Vehicle::factory()->create();
        $statusCompleted = TransactionStatus::factory()->create(['status' => 'Completed']);

        $transaction = Transaction::factory()->create([
            'user_id' => $this->user->id,
            'payment_id' => $payment->id,
            'driver_id' => $driver->id,
            'vehicle_id' => $vehicle->id,
            'price' => 300000,
            'driver_fee' => 50000,
            'transaction_status_id' => $statusCompleted->id,
        ]);

        $this->actingAs($this->user);
        $response = $this->get(route('receipt.download', ['payment' => $payment->id]));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');
        
        $expectedFilename = 'attachment; filename=receipt-rentlab-' . $payment->external_id . '.pdf';
        $response->assertHeader('Content-Disposition', $expectedFilename);
    }

    /** @test */
    public function tc13_it_prevents_downloading_a_receipt_for_an_unpaid_transaction(): void
    {
        // ARRANGE
        $payment = Payment::factory()->create(['status' => 'PENDING']);
        $vehicle = Vehicle::factory()->create();
        $driver = Driver::factory()->create();
        $status = TransactionStatus::factory()->create(['status' => 'On Payment']);

        Transaction::factory()->create([
            'user_id' => $this->user->id,
            'payment_id' => $payment->id,
            'vehicle_id' => $vehicle->id,
            'driver_id' => $driver->id,
            'transaction_status_id' => $status->id,
            'price' => 150000,
            'driver_fee' => 50000,
        ]);

        // ACT
        $this->actingAs($this->user);
        $response = $this->get(route('booking.history'));

        // ASSERT
        $response->assertStatus(200);
        $downloadUrl = route('receipt.download', ['payment' => $payment->id]);
        $response->assertDontSee($downloadUrl);
    }

    /** @test */
    public function tc14_a_user_cannot_download_a_receipt_belonging_to_another_user(): void
    {
        // ARRANGE
        $userA = $this->user;
        $userB = User::factory()->create();
        $vehicle = Vehicle::factory()->create();
        $driver = Driver::factory()->create();
        $status = TransactionStatus::factory()->create(['status' => 'Completed']); // Status manual

        $paymentUserB = Payment::factory()->create(['status' => 'PAID']);
        Transaction::factory()->create([
            'user_id' => $userB->id,
            'payment_id' => $paymentUserB->id,
            'vehicle_id' => $vehicle->id,
            'driver_id' => $driver->id,
            'transaction_status_id' => $status->id,
            'price' => 200000,
            'driver_fee' => 50000,
        ]);

        // ACT
        $this->actingAs($userA);
        $response = $this->get(route('receipt.download', ['payment' => $paymentUserB->id]));

        // ASSERT
        $response->assertForbidden();
    }
};