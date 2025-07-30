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
        $searchTerm = 'documents';
        $expectedQuestion = __('faq.q7');
        $unexpectedQuestion = __('faq.q8');

        $response = $this->get(route('faq.index', ['search' => $searchTerm]));

        $response->assertStatus(200);
        $response->assertSee($expectedQuestion);
        $response->assertDontSee($unexpectedQuestion);
    }

    /** @test */
    public function tc3_search_with_no_results_hides_all_questions(): void
    {
        $response = $this->get(route('faq.index', ['search' => 'xyz-istilah-ini-tidak-mungkin-ada-xyz']));

        $response->assertStatus(200);
        $response->assertSee(__('faq.no_results'));
        $response->assertDontSee(__('faq.q1'));
        $response->assertDontSee(__('faq.q7'));
        $response->assertDontSee(__('faq.q15'));
    }

    /** @test */
    public function tc4_search_is_case_insensitive(): void
    {
        $searchTerm = 'DOCUMENTS';
        $expectedQuestion = __('faq.q7');
        $unexpectedQuestion = __('faq.q8');

        $response = $this->get(route('faq.index', ['search' => $searchTerm]));

        $response->assertStatus(200);
        $response->assertSee($expectedQuestion);
        $response->assertDontSee($unexpectedQuestion);
    }

    /** @test */
    public function tc5_search_finds_partial_words(): void
    {
        $searchTerm = 'reserv';
        $expectedQuestion = __('faq.q2');
        $unexpectedQuestion = __('faq.q3');

        $response = $this->get(route('faq.index', ['search' => $searchTerm]));
        
        $response->assertStatus(200);
        $response->assertSee($expectedQuestion);
        $response->assertDontSee($unexpectedQuestion);
    }
    
    /** @test */
    public function tc6_search_with_multiple_results(): void
    {
        $searchTerm = 'car';

        $response = $this->get(route('faq.index', ['search' => $searchTerm]));

        $response->assertStatus(200);
        $response->assertSee(__('faq.q2'));
        $response->assertSee(__('faq.q4'));
        $response->assertSee(__('faq.q13'));
        $response->assertDontSee(__('faq.q6'));
    }

    /** @test */
    public function tc7_empty_search_returns_all_questions(): void
    {
        $response = $this->get(route('faq.index', ['search' => '']));

        $response->assertStatus(200);
        $response->assertSee(__('faq.q1'));
        $response->assertSee(__('faq.q30'));
        $response->assertDontSee(__('faq.no_results'));
    }

    /** @test */
    public function tc8_search_ignores_leading_and_trailing_spaces(): void
    {
        $searchTerm = '  documents  ';
        $expectedQuestion = __('faq.q7');
        $unexpectedQuestion = __('faq.q8');

        $response = $this->get(route('faq.index', ['search' => $searchTerm]));

        $response->assertStatus(200);
        $response->assertSee($expectedQuestion);
        $response->assertDontSee($unexpectedQuestion);
    }

    /** @test */
    public function tc9_search_term_is_repopulated_in_view(): void
    {
        $searchTerm = 'mileage';
        $response = $this->get(route('faq.index', ['search' => $searchTerm]));
    
        $response->assertStatus(200);
        $response->assertSee('<input name="search" id="faqSearch"', false);
        $response->assertSee("value=\"{$searchTerm}\"", false);
    }

    /** @test */
    public function tc10_search_finds_keyword_in_answer_body(): void
    {
        $searchTerm = 'agreement';

        $this->get(route('faq.index', ['search' => $searchTerm]))
             ->assertStatus(200)
             ->assertSee(__('faq.q30'))
             ->assertDontSee(__('faq.q7'));
    }

    /** @test */
    public function tc11_search_with_multiple_keywords_and_logic(): void
    {
        $this->get(route('faq.index', ['search' => 'rental car']))
             ->assertStatus(200)
             ->assertSee(__('faq.q2'))
             ->assertDontSee(__('faq.q1'));
    }


    /** @test */
    public function tc12_search_with_interspersed_characters_shows_no_results(): void
    {
        $this->get(route('faq.index', ['search' => 'c/./a-r']))
            ->assertStatus(200)
            ->assertSee(__('faq.no_results'))
            ->assertDontSee(__('faq.q2'));
    }

}