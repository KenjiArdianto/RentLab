<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

class LandingTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public static function languageProvider(): array
    {
        return [
            'Bahasa Inggris' => ['en'],
            'Bahasa Indonesia' => ['id'],
        ];
    }

    /**
     * @test
     * @dataProvider languageProvider
     */
    public function tc1_user_can_view_the_landing_page(string $lang): void
    {
        App::setLocale($lang);
        $response = $this->withSession(['lang' => $lang])->get(route('landing.index'));

        $response->assertStatus(200);
        $response->assertViewIs('landing');
        $response->assertSee(__('landing.hero_welcome'));
        $response->assertSee(__('landing.about_title'));
    }

    /**
     * @test
     * @dataProvider languageProvider
     */
    public function tc2_guest_can_see_login_and_register_buttons(string $lang): void
    {
        App::setLocale($lang);
        $response = $this->withSession(['lang' => $lang])->get(route('landing.index'));

        $response->assertSee(__('landing.login'));
        $response->assertSee(__('landing.register'));
        $response->assertDontSee('Welcome,');

        $response->assertSeeHtml('<a class="btn btn-primary rounded-pill w-100" href="'.route('login').'">'.__('landing.login').'</a>');
        $response->assertSeeHtml('<a class="btn btn-outline-primary rounded-pill w-100" href="'.route('register').'">'.__('landing.register').'</a>');
    }

    /**
     * @test
     * @dataProvider languageProvider
     */
    public function tc3_authenticated_user_sees_profile_dropdown(string $lang): void
    {
        App::setLocale($lang);
        $response = $this->actingAs($this->user)->withSession(['lang' => $lang])->get(route('landing.index'));

        $response->assertSee("Welcome, {$this->user->name}");
        $response->assertDontSee(__('landing.login'));
    }

    /**
     * @test
     * @dataProvider languageProvider
     */
    public function tc4_testimonial_slider_is_visible(string $lang): void
    {
        App::setLocale($lang);
        $response = $this->withSession(['lang' => $lang])->get(route('landing.index'));

        $response->assertSee(__('landing.testimonials_title'));
        $response->assertSee(__('landing.testimonial_1_author'));
    }

    /**
     * @test
     */
    public function tc5_search_form_validation_fails_without_start_date(): void
    {
        $formData = ['end_book_date' => now()->addDays(5)->format('Y-m-d'), 'vehicle_type' => 'car'];
        $response = $this->post(route('landing.search'), $formData);
        $response->assertSessionHasErrors('start_book_date');
    }

    /**
     * @test
     */
    public function tc6_search_form_validation_fails_if_end_date_is_before_start_date(): void
    {
        $formData = ['start_book_date' => now()->addDays(2)->format('Y-m-d'), 'end_book_date' => now()->addDay()->format('Y-m-d'), 'vehicle_type' => 'car'];
        $response = $this->post(route('landing.search'), $formData);
        $response->assertSessionHasErrors('end_book_date');
    }

    /**
     * @test
     */
    public function tc7_search_form_validation_fails_with_invalid_vehicle_type(): void
    {
        $formData = ['start_book_date' => now()->addDay()->format('Y-m-d'), 'end_book_date' => now()->addDays(2)->format('Y-m-d'), 'vehicle_type' => 'bicycle'];
        $response = $this->post(route('landing.search'), $formData);
        $response->assertSessionHasErrors('vehicle_type');
    }

    /**
     * @test
     */
    public function tc8_search_form_validation_passes_with_valid_data(): void
    {
        $formData = ['start_book_date' => now()->addDay()->format('Y-m-d'), 'end_book_date' => now()->addDays(2)->format('Y-m-d'), 'vehicle_type' => 'motorcycle'];
        $this->withoutExceptionHandling();
        $response = $this->post(route('landing.search'), $formData);
        $response->assertSessionHasNoErrors();
        // $response->assertRedirect();
        $response->assertRedirect(route('search.results.page', $formData));
    }

    /**
     * @test
     */
    public function tc9_search_form_validation_fails_with_invalid_date_format(): void
    {
        $formData = ['start_book_date' => 'ini-bukan-tanggal', 'end_book_date' => now()->addDays(2)->format('Y-m-d'), 'vehicle_type' => 'car'];
        $response = $this->post(route('landing.search'), $formData);
        $response->assertSessionHasErrors('start_book_date');
    }

    /**
     * @test
     */
    public function tc10_search_form_validation_fails_without_vehicle_type(): void
    {
        $formData = ['start_book_date' => now()->addDay()->format('Y-m-d'), 'end_book_date' => now()->addDays(2)->format('Y-m-d')];
        $response = $this->post(route('landing.search'), $formData);
        $response->assertSessionHasErrors('vehicle_type');
    }

    /**
     * @test
     */
    public function tc11_language_switcher_sets_session_correctly(): void
    {
        $this->withSession(['lang' => 'en']);
        $response = $this->post('/lang', ['lang' => 'id']);
        $response->assertRedirect();
        $this->assertEquals('id', session('lang'));
    }

    /**
     * @test
     */
    public function tc12_search_form_validation_passes_with_same_start_and_end_date(): void
    {
        $today = now()->format('Y-m-d');
        $formData = ['start_book_date' => $today, 'end_book_date' => $today, 'vehicle_type' => 'car'];
        $response = $this->post(route('landing.search'), $formData);
        $response->assertSessionHasNoErrors();
    }

    /**
     * @test
     */
    public function tc13_search_form_validation_fails_with_past_date(): void
    {
        $formData = [
            'start_book_date' => now()->subDay()->format('Y-m-d'),
            'end_book_date'   => now()->addDays(2)->format('Y-m-d'),
            'vehicle_type'    => 'car',
        ];

        $response = $this->post(route('landing.search'), $formData);
        $response->assertSessionHasErrors('start_book_date');
    }
}