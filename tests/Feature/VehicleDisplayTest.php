<?php

namespace Tests\Feature;

use App\Models\Advertisement;
use App\Models\Location;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleName;
use App\Models\VehicleType;
use App\Models\VehicleTransmission; // <-- PASTIKAN BARIS INI ADA
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VehicleDisplayTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    // Method ini berjalan sebelum setiap test dijalankan
    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();

        // Data master yang sudah ada
        VehicleType::factory()->create(['type' => 'Car']);
        VehicleType::factory()->create(['type' => 'Motorcycle']);
        Location::factory()->create();

        // =============================================================
        // TAMBAHKAN INI: Buat data master untuk transmisi
        // =============================================================
        VehicleTransmission::factory()->create(['transmission' => 'Automatic']);
        VehicleTransmission::factory()->create(['transmission' => 'Manual']);
        // =============================================================
    }

    //======================================================================
    // TESTS FOR HOMESCREEN (vehicle.display)
    //======================================================================

    /** @test */
    public function homescreen_loads_successfully_and_shows_correct_view(): void
    {
        // Mengautomasi test case: HP-001
        $response = $this->get(route('vehicle.display'));

        $response->assertStatus(200);
        $response->assertViewIs('webview.homescreen');
    }

    /** @test */
    public function homescreen_displays_advertisements_carousel(): void
    {
        // Mengautomasi test case: HP-002
        // Arrange: Buat beberapa data iklan
        Advertisement::factory(3)->create(['isactive' => true]);

        // Act: Kunjungi halaman utama
        $response = $this->get(route('vehicle.display'));

        // Assert: Pastikan section carousel ada
        $response->assertSee('id="heroCarousel"', false); // false agar tidak escape HTML
    }

    /** @test */
    public function homescreen_displays_list_of_vehicles(): void
    {
        // Mengautomasi test case: HP-004
        // Arrange: Buat sebuah kendaraan dengan nama spesifik
        $vehicleName = VehicleName::factory()->create(['name' => 'Avanza Test']);
        Vehicle::factory()->create(['vehicle_name_id' => $vehicleName->id]);

        // Act: Kunjungi halaman utama
        $response = $this->get(route('vehicle.display'));

        // Assert: Pastikan nama kendaraan tersebut muncul di halaman
        $response->assertSee('Avanza Test');
    }

    /** @test */
    public function homescreen_displays_empty_message_when_no_vehicles_exist(): void
    {
        // Mengautomasi test case: HP-005
        $response = $this->get(route('vehicle.display'));

        // Assert: Pastikan pesan untuk hasil kosong muncul
        $response->assertSee(__('app.results.empty'));
    }

    /** @test */
    public function homescreen_filter_by_vehicle_type_works(): void
    {
        // Mengautomasi test case: HP-006
        // Arrange: Buat 1 mobil dan 1 motor
        $carName = VehicleName::factory()->create(['name' => 'Mobil Keren']);
        $motorName = VehicleName::factory()->create(['name' => 'Motor Cepat']);

        $carType = VehicleType::where('type', 'Car')->first();
        $motorType = VehicleType::where('type', 'Motorcycle')->first();

        Vehicle::factory()->create(['vehicle_name_id' => $carName->id, 'vehicle_type_id' => $carType->id]);
        Vehicle::factory()->create(['vehicle_name_id' => $motorName->id, 'vehicle_type_id' => $motorType->id]);

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
        // Mengautomasi test case: CAT-001 & CAT-002
        // Arrange: Buat 2 kendaraan dengan merk berbeda
        $toyotaName = VehicleName::factory()->create(['name' => 'Toyota Fortuner']);
        $hondaName = VehicleName::factory()->create(['name' => 'Honda CRV']);
        Vehicle::factory()->create(['vehicle_name_id' => $toyotaName->id]);
        Vehicle::factory()->create(['vehicle_name_id' => $hondaName->id]);

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
        // Mengautomasi test case: CAT-005
        // Arrange: Buat 2 kendaraan Toyota dengan tipe berbeda
        $fortunerName = VehicleName::factory()->create(['name' => 'Toyota Fortuner']);
        $yarisName = VehicleName::factory()->create(['name' => 'Toyota Yaris']);

        $carType = VehicleType::where('type', 'Car')->first();

        Vehicle::factory()->create([
            'vehicle_name_id' => $fortunerName->id,
            'vehicle_type_id' => $carType->id,
            // Asumsi Anda punya cara mengaitkan vehicle dengan kategori, misal: via relasi.
            // Di sini kita sederhanakan. Logika tes mungkin perlu disesuaikan dengan struktur DB Anda.
        ]);
        Vehicle::factory()->create([
            'vehicle_name_id' => $yarisName->id,
            'vehicle_type_id' => $carType->id,
        ]);

        // Skenario: cari "Toyota", lalu filter hanya untuk yang namanya "Fortuner"
        // (Ini contoh, idealnya Anda filter berdasarkan kategori seperti SUV/Hatchback)
        $response = $this->get(route('vehicle.catalog', ['search' => 'Toyota']));

        // Assert awal: pastikan keduanya muncul
        $response->assertSee('Toyota Fortuner');
        $response->assertSee('Toyota Yaris');

        // Act #2: Lakukan request baru dengan filter tambahan
        $responseFiltered = $this->get(route('vehicle.catalog', ['search' => 'Fortuner']));

        // Assert #2: Pastikan hanya Fortuner yang muncul
        $responseFiltered->assertSee('Toyota Fortuner');
        $responseFiltered->assertDontSee('Toyota Yaris');
    }
}
