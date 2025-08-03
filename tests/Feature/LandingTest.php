<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Carbon\Carbon;

class LandingTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     * TC1: Checking if a user can successfully view the main landing page.
     */
    public function tc1_landing_page_loads_successfully()
    {
        $response = $this->get(route('landing.index'));
        $response->assertStatus(200);
        $response->assertViewIs('landing');
        $response->assertSee(__('landing.hero_welcome'));
    }

    /**
     * @test
     * TC2: Checking if the testimonials section is visible on the landing page.
     */
    public function tc2_testimonials_section_is_visible()
    {
        $response = $this->get(route('landing.index'));
        $response->assertStatus(200);
        $response->assertSee(__('landing.testimonials_title'));
        $response->assertSee(__('landing.testimonial_1_author'));
    }

    /**
     * @test
     * TC3: Checking if a guest user (not logged in) sees the 'Login' and 'Register' buttons.
     */
    public function tc3_guest_sees_login_and_register_buttons()
    {
        $response = $this->get(route('landing.index'));
        $response->assertSee(__('landing.login'));
        $response->assertSee(__('landing.register'));
        $response->assertDontSee('Welcome,');
    }

    /**
     * @test
     * TC4: Checking if an authenticated user sees their name and not the login buttons.
     */
    public function tc4_authenticated_user_sees_their_name_in_navbar()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('landing.index'));
        $response->assertSee("Welcome, " . $user->name);
        $response->assertDontSee(__('landing.login'));
        $response->assertDontSee(__('landing.register'));
    }

    private function getValidSearchData($overrides = [])
    {
        return array_merge([
            'start_book_date' => now()->addDays()->format('Y-m-d'),
            'end_book_date'   => now()->addDays(3)->format('Y-m-d'),
            'vehicle_type'    => 'motorcycle',
        ], $overrides);
    }

    /** @test */
    public function tc5_search_fails_when_start_date_is_not_provided()
    {
        $data = $this->getValidSearchData(['start_book_date' => '']);
        $response = $this->get(route('landing.search', $data));
        $response->assertSessionHasErrors('start_book_date');
    }

    /** @test */
    public function tc6_search_fails_when_end_date_is_before_start_date()
    {
        $data = $this->getValidSearchData([
            'start_book_date' => now()->addDays(3)->format('Y-m-d'),
            'end_book_date'   => now()->addDay()->format('Y-m-d'),
        ]);
        $response = $this->get(route('landing.search', $data));
        $response->assertSessionHasErrors('end_book_date');
    }

    /** @test */
    public function tc7_search_fails_for_an_invalid_vehicle_type()
    {
        $data = $this->getValidSearchData(['vehicle_type' => 'bicycle']);
        $response = $this->get(route('landing.search', $data));
        $response->assertSessionHasErrors('vehicle_type');
    }

    /** @test */
    public function tc8_search_fails_for_an_invalid_start_date_format()
    {
        $data = $this->getValidSearchData(['start_book_date' => 'ini-bukan-tanggal']);
        $response = $this->get(route('landing.search', $data));
        $response->assertSessionHasErrors('start_book_date');
    }

    /** @test */
    public function tc9_search_fails_when_vehicle_type_is_not_provided()
    {
        $data = $this->getValidSearchData(['vehicle_type' => '']);
        $response = $this->get(route('landing.search', $data));
        $response->assertSessionHasErrors('vehicle_type');
    }

    /** @test */
    public function tc10_search_fails_when_start_date_is_in_the_past()
    {
        $data = $this->getValidSearchData(['start_book_date' => now()->subDay()->format('Y-m-d')]);
        $response = $this->get(route('landing.search', $data));
        $response->assertSessionHasErrors('start_book_date');
    }

    /** @test */
    public function tc11_search_passes_with_complete_and_valid_data()
    {
        $data = $this->getValidSearchData(['vehicle_type' => 'car']);
        $response = $this->get(route('landing.search', $data));
        $response->assertSessionHasNoErrors();
        $expectedRedirectParams = [
            'vehicle_type'    => 'Car',
            'start_book_date' => $data['start_book_date'],
            'end_book_date'   => $data['end_book_date'],
            'min_price'       => '',
            'max_price'       => '',
        ];
        $response->assertRedirect(route('vehicle.display', $expectedRedirectParams));
    }

    /** @test */
    public function tc12_search_passes_for_single_day_with_valid_data()
    {
        $date = now()->addDay()->format('Y-m-d');
        $data = $this->getValidSearchData([
            'start_book_date' => $date,
            'end_book_date'   => $date,
            'vehicle_type'    => 'motorcycle'
        ]);
        $response = $this->get(route('landing.search', $data));
        $response->assertSessionHasNoErrors();
        $expectedRedirectParams = [
            'vehicle_type'    => 'Motor',
            'start_book_date' => $data['start_book_date'],
            'end_book_date'   => $data['end_book_date'],
            'min_price'       => '',
            'max_price'       => '',
        ];
        $response->assertRedirect(route('vehicle.display', $expectedRedirectParams));
    }

    /**
     * @test
     * TC13: Checking if the language switcher sets the session correctly.
     */
    public function tc13_language_switcher_works_correctly()
    {
        $this->withSession(['locale' => 'id']);
        $response = $this->get(route('lang.switch', 'en'));
        $response->assertRedirect();
        $this->assertEquals('en', session('locale'));
    }
}