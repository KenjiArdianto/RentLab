<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RentLab</title>

    <!-- Bootstrap 5 CSS -->
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"> --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Your Vite assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Custom styles for the search bar focus effect */
        .search-container {
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }
        .search-container:focus-within {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
        .search-input-inner:focus {
            outline: none !important;
            box-shadow: none !important;
        }
        /* Styling for the new bottom navigation bar */
        .bottom-nav {
            border-top: 1px solid #dee2e6;
        }
        .bottom-nav .nav-link {
            font-size: 0.75rem;
            color: #6c757d;
        }
        .bottom-nav .nav-link.active,
        .bottom-nav .nav-link:hover {
            color: #0d6efd;
        }
        .bottom-nav .nav-link i {
            font-size: 1.5rem;
        }
    </style>

    {{-- Library CSS tambahan --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
</head>
<body>
<<<<<<< HEAD

    
    {{-- <iframe width="425" height="755" src="https://www.youtube.com/embed/3XrzMkmOQQA" title="KAWASAKI CAGO KRICO ESTRIPPER" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe> --}}

=======
>>>>>>> 720c415b7b9d7091c471953989a35203d2ac5290
    <header class="sticky-top bg-light shadow-sm">
        <!-- Top bar for language selection -->
        <nav class="navbar mt-0 mb-0 me-lg-0 p-0 justify-content-end d-flex">
            <div class="container-fluid justify-content-end">
                <a class="nav-link py-1 px-3 small" href="{{ route('faq.index') }}">@lang('app.nav.faq')</a>
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle py-1 px-3 small" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-translate"></i> @lang('app.nav.language')
                    </a>
                    @php
                        $available_locales = ['en' => 'English', 'id' => 'Bahasa Indonesia'];
                    @endphp
                    <ul class="dropdown-menu dropdown-menu-end">
                        @foreach ($available_locales as $locale_code => $locale_name)
                            @if ($locale_code !== app()->getLocale())
                                <li><a class="dropdown-item" href="{{ url('/lang/' . $locale_code) }}">{{ $locale_name }}</a></li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Main navigation bar -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light px-2 pt-2 pb-1 p-lg-0">
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center w-100 pt-0">

                    <!-- Brand: Hidden on small screens, visible on large -->
                    {{-- FIX: Menambahkan parameter locale ke route 'vehicle.display' --}}
                    <a class="navbar-brand fs-3 fw-bold mx-3 d-none d-lg-block" href="{{ route('vehicle.display', ['locale' => app()->getLocale()]) }}">RentLab</a>

                    <!-- Desktop Search Form -->
                    <div class="flex-grow-1 mx-lg-4 d-none d-lg-flex">
                        {{-- FIX: Menambahkan parameter locale ke route 'vehicle.catalog' --}}
                        <form class="w-100" action="{{ route('vehicle.catalog', ['locale' => app()->getLocale()]) }}" method="GET" role="search">
                            <div class="p-1 d-flex flex-row align-items-center form-control border-primary ps-3 pe-3 w-100 rounded-pill search-container">
                                <label for="search-input-desktop" class="search-icon-label"><i class="bi bi-search"></i></label>
                                <div class="container-fluid p-0">
                                    <input id="search-input-desktop" name="search" class="container-fluid border-0 search-input-inner pe-0 ps-3 bg-transparent" type="search" placeholder="@lang('app.nav.search_placeholder')" value="{{ request('search') }}">
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Mobile Search Form -->
                    <div class="flex-grow-1 me-2 d-lg-none">
                        {{-- FIX: Menambahkan parameter locale ke route 'vehicle.catalog' --}}
                        <form class="w-100" action="{{ route('vehicle.catalog', ['locale' => app()->getLocale()]) }}" method="GET" role="search">
                             <div class="p-1 d-flex flex-row align-items-center form-control border-primary ps-3 pe-3 w-100 rounded-pill search-container">
                                <label for="search-input-mobile" class="search-icon-label"><i class="bi bi-search"></i></label>
                                <div class="container-fluid p-0">
                                    <input id="search-input-mobile" name="search" class="container-fluid border-0 search-input-inner pe-0 ps-3 bg-transparent" type="search" placeholder="@lang('app.nav.search_placeholder_mobile')" value="{{ request('search') }}">
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Right-side Icons -->
                    <ul class="navbar-nav flex-row align-items-center">
                        <li class="nav-item"><a class="nav-link p-2 px-4" href="{{ route('cart') }}" title="@lang('app.nav.cart')"><i class="bi bi-cart fs-4"></i></a></li>
                        <li class="nav-item d-none d-lg-inline-block"><a class="nav-link p-2 px-4" href="{{ route('booking.history') }}" title="@lang('app.nav.transactions')"><i class="bi bi-receipt fs-4"></i></a></li>
                        <li class="nav-item d-none d-lg-inline-block"><a class="nav-link p-2 px-4" href="{{ route('view.profile') }}" title="@lang('app.nav.account')"><i class="bi bi-person-circle fs-4"></i></a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>


    <main class = "container-fluid p-0 m-0">
        {{ $slot }}
    </main>

    <!-- Bottom Navigation Bar (Mobile Only) -->
    <nav class="navbar fixed-bottom navbar-light bg-light d-lg-none bottom-nav p-0">
        <div class="container-fluid">
            <ul class="navbar-nav d-flex flex-row justify-content-around w-100">
                <li class="nav-item">
                    {{-- FIX: Menambahkan parameter locale ke route 'vehicle.display' --}}
                    <a class="nav-link d-flex flex-column align-items-center @if(request()->routeIs('vehicle.display')) active @endif" href="{{ route('vehicle.display', ['locale' => app()->getLocale()]) }}" title="@lang('app.nav.home')">
                        <i class="bi bi-house-door-fill"></i>
                        <span>@lang('app.nav.home')</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex flex-column align-items-center" href="#" title="@lang('app.nav.transactions')">
                        <i class="bi bi-receipt"></i>
                        <span>@lang('app.nav.transactions')</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex flex-column align-items-center" href="#" title="@lang('app.nav.account')">
                        <i class="bi bi-person"></i>
                        <span>@lang('app.nav.account')</span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    {{-- Memuat script locale flatpickr secara dinamis --}}
    @if(app()->getLocale() == 'id')
        <script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>
    @endif

    @stack('scripts')

</body>
</html>
