<?php

namespace Tests\Feature;

use App\Models\Advertisement;
use App\Models\Location;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleCategory;
use App\Models\VehicleName;
use App\Models\VehicleType;
use App\Models\VehicleTransmission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VehicleDisplayTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected VehicleTransmission $transmission;

    // Method ini berjalan sebelum setiap test dijalankan
    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();

        // Buat data master dengan ID yang pasti dan konsisten
        VehicleType::factory()->create(['id' => 1, 'type' => 'Car']);
        VehicleType::factory()->create(['id' => 2, 'type' => 'Motorcycle']);

        VehicleName::factory()->count(20)->sequence(fn ($s) => ['name' => 'Vehicle Name ' . $s->index])->create();

        Location::factory()->create(['location' => 'Test Location']);
        $this->transmission = VehicleTransmission::factory()->create(['transmission' => 'Automatic']);

        VehicleCategory::factory()->count(5)->sequence(fn ($s) => ['category' => 'Category ' . $s->index])->create();
    }

    //======================================================================
    // TESTS FOR HOMESCREEN (vehicle.display)
    //======================================================================

    /** @test */
    public function homescreen_loads_successfully_and_shows_correct_view(): void
    {
        $response = $this->get(route('vehicle.display'));

        $response->assertStatus(200);
        $response->assertViewIs('webview.homescreen');
    }

    /** @test */
    public function homescreen_displays_advertisements_carousel(): void
    {
        Advertisement::factory(3)->create(['isactive' => true]);
        $response = $this->get(route('vehicle.display'));
        $response->assertSee('id="heroCarousel"', false);
    }

    /** @test */
    public function homescreen_displays_list_of_vehicles(): void
    {
        // Arrange: Pastikan kendaraan yang dibuat memiliki semua relasi yang dibutuhkan
        $vehicleName = VehicleName::factory()->create(['name' => 'Avanza Test Spesifik']);
        Vehicle::factory()->create([
            'vehicle_type_id' => 1,
            'vehicle_transmission_id' => $this->transmission->id,
            'vehicle_name_id' => $vehicleName->id,
        ]);

        // Act: Kunjungi halaman utama
        $response = $this->get(route('vehicle.display'));

        // Assert: Cek apakah nama kendaraan yang spesifik itu muncul di halaman
        $response->assertStatus(200);
        // ==========================================================
        // PERBAIKAN FINAL: Cari nama kendaraan, bukan kelas CSS
        // ==========================================================
        $response->assertSee('Avanza Test Spesifik');
    }

    /** @test */
    public function homescreen_displays_empty_message_when_no_vehicles_exist(): void
    {
        $response = $this->get(route('vehicle.display'));
        $response->assertSee(__('app.results.empty'));
    }

    /** @test */
    public function homescreen_filter_by_vehicle_type_works(): void
    {
        // Arrange: Buat 1 mobil dan 1 motor dengan semua relasi yang valid
        $carName = VehicleName::factory()->create(['name' => 'Mobil Keren']);
        $motorName = VehicleName::factory()->create(['name' => 'Motor Cepat']);

        $carType = VehicleType::where('type', 'Car')->first();
        $motorType = VehicleType::where('type', 'Motorcycle')->first();
        $transmission = VehicleTransmission::first();

        Vehicle::factory()->create([
            'vehicle_name_id' => $carName->id,
            'vehicle_type_id' => $carType->id,
            'vehicle_transmission_id' => $transmission->id,
        ]);
        Vehicle::factory()->create([
            'vehicle_name_id' => $motorName->id,
            'vehicle_type_id' => $motorType->id,
            'vehicle_transmission_id' => $transmission->id,
        ]);

        // Act: Kunjungi halaman dengan filter 'Car'
        $response = $this->get(route('vehicle.display', ['Tipe_Kendaraan' => 'Car']));

        // Assert: Pastikan hanya nama mobil yang tampil, dan nama motor tidak
        $response->assertSee('Mobil Keren');
        $response->assertDontSee('Motor Cepat');
    }

    //======================================================================
    // TESTS FOR CATALOG PAGE (vehicle.catalog)
    //======================================================================

    /** @test */
    public function catalog_page_shows_relevant_search_results(): void
    {
        // Arrange: Buat 2 kendaraan dengan merk dan transmisi yang valid
        $toyotaName = VehicleName::factory()->create(['name' => 'Toyota Fortuner']);
        $hondaName = VehicleName::factory()->create(['name' => 'Honda CRV']);
        $transmission = VehicleTransmission::first();

        Vehicle::factory()->create([
            'vehicle_name_id' => $toyotaName->id,
            'vehicle_type_id' => 1,
            'vehicle_transmission_id' => $transmission->id,
        ]);
        Vehicle::factory()->create([
            'vehicle_name_id' => $hondaName->id,
            'vehicle_type_id' => 1,
            'vehicle_transmission_id' => $transmission->id,
        ]);

        // Act: Lakukan pencarian dengan kata kunci "Toyota"
        $response = $this->get(route('vehicle.catalog', ['search' => 'Toyota']));

        // Assert:
        $response->assertStatus(200);
        $response->assertViewIs('webview.catalog');
        $response->assertSee('Toyota Fortuner');
        $response->assertDontSee('Honda CRV');
        $response->assertSee(__('app.results.showing_for', ['term' => 'Toyota']));
    }

    /** @test */
    public function catalog_page_can_apply_filters_on_top_of_search_results(): void
    {
        // Arrange: Buat 2 kendaraan Toyota dengan semua relasi yang valid
        $fortunerName = VehicleName::factory()->create(['name' => 'Toyota Fortuner']);
        $yarisName = VehicleName::factory()->create(['name' => 'Toyota Yaris']);
        $carType = VehicleType::where('type', 'Car')->first();
        $transmission = VehicleTransmission::first();

        Vehicle::factory()->create([
            'vehicle_name_id' => $fortunerName->id,
            'vehicle_type_id' => $carType->id,
            'vehicle_transmission_id' => $transmission->id,
        ]);
        Vehicle::factory()->create([
            'vehicle_name_id' => $yarisName->id,
            'vehicle_type_id' => $carType->id,
            'vehicle_transmission_id' => $transmission->id,
        ]);

        // Skenario: cari "Toyota"
        $response = $this->get(route('vehicle.catalog', ['search' => 'Toyota']));
        $response->assertSee('Toyota Fortuner');
        $response->assertSee('Toyota Yaris');

        // Act #2: Lakukan request baru dengan filter tambahan
        $responseFiltered = $this->get(route('vehicle.catalog', ['search' => 'Fortuner']));

        // Assert #2: Pastikan hanya Fortuner yang muncul
        $responseFiltered->assertSee('Toyota Fortuner');
        $responseFiltered->assertDontSee('Toyota Yaris');
    }
}
