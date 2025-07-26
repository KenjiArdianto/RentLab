@php
    $currLang = session()->get('lang', 'en');
    app()->setLocale($currLang);
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('faq.title') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    
    <link rel="stylesheet" href="{{ asset('build/assets/CSS/faq.css') }}">
</head>
<x-layout>
    <div class="container pt-4 pb-5">
        <h1 class="text-center mb-4">{{ __('faq.header') }}</h1>

        <form class="d-flex justify-content-start align-items-center mb-4" role="search">
            <div class="p-1 d-flex flex-row justify-content-start align-items-center form-control border-primary ps-3 pe-3 w-100 rounded-4 search-container">
                
                <label for="faqSearch" class="search-icon-label">
                    <img src="{{ asset('build/assets/icons8-search-50.png') }}" alt="" width="20" height="20">
                </label>

                <div class="container-fluid p-0">
                    <input id="faqSearch" class="container-fluid border-0 search-input-inner pe-0 ps-3" type="search" placeholder="{{ __('faq.search_placeholder') }}" aria-label="Search">
                </div>
                
            </div>
        </form>

        <div id="noResults" class="no-results-message" style="display: none;">
            {{ __('faq.no_results') }}
        </div>

        <div class="accordion" id="faqAccordion">
            @for ($i = 1; $i <= 30; $i++)
                @if (Lang::has('faq.q'.$i) && Lang::has('faq.a'.$i))
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading{{$i}}">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{$i}}" aria-expanded="false" aria-controls="collapse{{$i}}">
                                {{ __('faq.q'.$i) }}
                            </button>
                        </h2>
                        <div id="collapse{{$i}}" class="accordion-collapse collapse" aria-labelledby="heading{{$i}}" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                {!! __('faq.a'.$i) !!}
                            </div>
                        </div>
                    </div>
                @endif
            @endfor
            
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    
    <script>
        document.getElementById('faqSearch').addEventListener('input', function(event) {
            let searchTerm = event.target.value.toLowerCase();
            let faqItems = document.querySelectorAll('.accordion-item');
            let noResultsMessage = document.getElementById('noResults');
            let resultsFound = false;
            
            faqItems.forEach(function(item) {
                let question = item.querySelector('.accordion-button').innerText.toLowerCase();
                if (question.includes(searchTerm)) {
                    item.style.display = 'block';
                    resultsFound = true;
                } else {
                    item.style.display = 'none';
                }
            });

            if (!resultsFound) {
                noResultsMessage.style.display = 'block';
            } else {
                noResultsMessage.style.display = 'none';
            }
        });
    </script>
</x-layout>
</html>