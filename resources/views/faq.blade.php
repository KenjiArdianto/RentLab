<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('faq.title') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('faq_assets/css/faq.css') }}">
</head>
<x-layout>
    <div class="container pt-4 pb-5">
        <h1 class="text-center mb-4">{{ __('faq.header') }}</h1>

        <form class="d-flex justify-content-start align-items-center mb-4" role="search" method="GET" action="{{ route('faq.index') }}">
            <div class="p-1 d-flex flex-row justify-content-start align-items-center form-control border-primary ps-3 pe-3 w-100 rounded-4 search-container">
                <label for="faqSearch" class="search-icon-label">
                    <img src="{{ asset('faq_assets/images/SearchLogo.png') }}" alt="" width="20" height="20">
                </label>
                <div class="container-fluid p-0">
                    <input name="search" id="faqSearch" class="container-fluid border-0 search-input-inner pe-0 ps-3" type="search" placeholder="{{ __('faq.search_placeholder') }}" aria-label="Search" value="{{ request('search') }}">
                </div>
            </div>
        </form>

        @if($noResults)
            <div class="no-results-message">
                {{ __('faq.no_results') }}
            </div>
        @endif

        <div class="accordion" id="faqAccordion">
            @foreach ($faqs as $faq)
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading{{$faq['id']}}">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{$faq['id']}}" aria-expanded="false" aria-controls="collapse{{$faq['id']}}">
                            {{ $faq['question'] }}
                        </button>
                    </h2>
                    <div id="collapse{{$faq['id']}}" class="accordion-collapse collapse" aria-labelledby="heading{{$faq['id']}}" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            {!! $faq['answer'] !!}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- @push('scripts')

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    
    {{-- SEMUA SCRIPT CUSTOM DIHAPUS, karena sudah ditangani server & Bootstrap default --}}
</x-layout>