<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;
use Illuminate\View\View;

class FaqController extends Controller
{
    /**
     * Menampilkan halaman FAQ dan mengatur alur logika pencarian.
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $searchTerm = trim($request->input('search'));
        $faqs = $this->getAllFaqs();
        $noResults = false;
        if (!empty($searchTerm)) {
            $keywords = explode(' ', strtolower($searchTerm));
            $faqs = $this->filterFaqsByKeywords($faqs, $keywords);
            if (count($faqs) === 0) {
                $noResults = true;
            }
        }
        return view('faq', [
            'faqs' => $faqs,
            'noResults' => $noResults,
        ]);
    }

    /**
     * Mengambil semua data FAQ dari file bahasa.
     *
     * @return array
     */
    private function getAllFaqs(): array
    {
        $allFaqs = [];
        for ($i = 1; $i <= 30; $i++) {
            if (Lang::has('faq.q' . $i) && Lang::has('faq.a' . $i)) {
                $allFaqs[] = [
                    'id' => $i,
                    'question' => __('faq.q' . $i),
                    'answer' => __('faq.a' . $i)
                ];
            }
        }
        return $allFaqs;
    }

    /**
     * Memfilter daftar FAQ berdasarkan kata kunci yang diberikan.
     * @param array $faqs
     * @param array $keywords
     * @return array
     */
    private function filterFaqsByKeywords(array $faqs, array $keywords): array
    {
        if (empty($keywords)) {
            return [];
        }

        return array_filter($faqs, function ($faq) use ($keywords) {
            $textToSearch = strtolower($faq['question'] . ' ' . $faq['answer']);
            foreach ($keywords as $keyword) {
                if (!Str::contains($textToSearch, $keyword)) {
                    return false;
                }
            }
            return true;
        });
    }
}
