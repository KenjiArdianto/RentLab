<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FaqTest extends TestCase
{
    /** @test */
    public function tc1_faq_page_displays_multiple_questions(): void
    {
        $response = $this->get(route('faq.index'));

        $response->assertStatus(200);
        $response->assertViewIs('faq');
        $response->assertSee(__('faq.q1'));
        $response->assertSee(__('faq.q5'));
        $response->assertSee(__('faq.q10'));
        $response->assertSee(__('faq.q15'));
        $response->assertSee(__('faq.q20'));
        $response->assertSee(__('faq.q25'));
        $response->assertSee(__('faq.q30'));
    }

    /** @test */
    public function tc2_search_correctly_filters_results_with_specific_keyword(): void
    {
        $this->get(route('faq.index', ['search' => 'documents']))
            ->assertStatus(200)
            ->assertSeeText(__('faq.q7'))
            ->assertDontSeeText(__('faq.q8'));
    }

    /** @test */
    public function tc3_search_with_no_results_hides_all_questions(): void
    {
        $this->get(route('faq.index', ['search' => 'xyz-nonexistent-keyword']))
            ->assertStatus(200)
            ->assertSeeText(__('faq.no_results'))
            ->assertDontSeeText(__('faq.q1'));
    }

    /** @test */
    public function tc4_search_is_case_insensitive(): void
    {
        $this->get(route('faq.index', ['search' => 'DOCUMENTS']))
            ->assertStatus(200)
            ->assertSeeText(__('faq.q7'))
            ->assertDontSeeText(__('faq.q8'));
    }

    /** @test */
    public function tc5_search_finds_partial_words(): void
    {
        $this->get(route('faq.index', ['search' => 'reserv']))
            ->assertStatus(200)
            ->assertSeeText(__('faq.q2'))
            ->assertDontSeeText(__('faq.q3'));
    }
    
    /** @test */
    public function tc6_search_with_multiple_results(): void
    {
        $this->get(route('faq.index', ['search' => 'car']))
            ->assertStatus(200)
            ->assertSeeText(__('faq.q2'))
            ->assertSeeText(__('faq.q4'))
            ->assertSeeText(__('faq.q13'))
            ->assertDontSeeText(__('faq.q6'));
    }

    /** @test */
    public function tc7_empty_search_returns_all_questions(): void
    {
        $this->get(route('faq.index', ['search' => '']))
            ->assertStatus(200)
            ->assertSeeText(__('faq.q1'))
            ->assertSeeText(__('faq.q30'))
            ->assertDontSeeText(__('faq.no_results'));
    }

    /** @test */
    public function tc8_search_ignores_leading_and_trailing_spaces(): void
    {
        $this->get(route('faq.index', ['search' => '  documents  ']))
            ->assertStatus(200)
            ->assertSeeText(__('faq.q7'))
            ->assertDontSeeText(__('faq.q8'));
    }

    /** @test */
    public function tc9_search_term_is_repopulated_in_view(): void
    {
        $searchTerm = 'mileage';

        $this->get(route('faq.index', ['search' => $searchTerm]))
            ->assertStatus(200)
            ->assertSee("value=\"{$searchTerm}\"", false);
    }

    /** @test */
    public function tc10_search_finds_keyword_in_answer_body(): void
    {
        $this->get(route('faq.index', ['search' => 'agreement']))
             ->assertStatus(200)
             ->assertSeeText(__('faq.q30'))
             ->assertDontSeeText(__('faq.q1'));
    }

    /** @test */
    public function tc11_search_with_multiple_keywords_and_logic(): void
    {
        $this->get(route('faq.index', ['search' => 'rental car']))
             ->assertStatus(200)
             ->assertSeeText(__('faq.q2'))
             ->assertDontSeeText(__('faq.q3'));
    }


    /** @test */
    public function tc12_search_with_interspersed_characters_shows_no_results(): void
    {
        $this->get(route('faq.index', ['search' => 'c/./a-r']))
            ->assertStatus(200)
            ->assertSeeText(__('faq.no_results'))
            ->assertDontSeeText(__('faq.q2'));
    }

}