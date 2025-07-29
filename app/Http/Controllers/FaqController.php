<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;

class FaqController extends Controller
{
    public function index(Request $request)
    {
        $searchTerm = trim($request->input('search'));
        $allFaqs = [];

        for ($i = 1; $i <= 30; $i++) {
            if (Lang::has('faq.q'.$i) && Lang::has('faq.a'.$i)) {
                $allFaqs[] = [ 'id' => $i, 'question' => __('faq.q'.$i), 'answer' => __('faq.a'.$i) ];
            }
        }

        $filteredFaqs = $allFaqs;
        $noResults = false;

        if (!empty($searchTerm)) {
            $filteredFaqs = array_filter($allFaqs, function ($faq) use ($searchTerm) {
                return Str::contains(strtolower($faq['question']), strtolower($searchTerm));
            });

            if (count($filteredFaqs) === 0) {
                $noResults = true;
            }
        }

        return view('faq', [
            'faqs' => $filteredFaqs,
            'noResults' => $noResults,
        ]);
    }
}