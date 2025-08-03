<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ __('landing.title') }}</title>

        <link rel="stylesheet" href="{{ asset('landing_assets/CSS/landing.css') }}">

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    </head>
    <body>
        <header class="sticky-top bg-light shadow-sm">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid px-4">
                    <a class="navbar-brand fs-3 fw-bold" href="{{ route('landing.index') }}">RentLab</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="mainNavbar">
                        <ul class="navbar-nav ms-auto align-items-center">
                            <div class="nav-item dropdown me-lg-2">
                                <a class="btn btn-outline-primary rounded-pill dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-translate me-2"></i>
                                    <span>{{ strtoupper(app()->getLocale()) }}</span>
                                </a>
                                @php
                                    $available_locales = ['en' => 'English', 'id' => 'Bahasa Indonesia'];
                                @endphp
                                <ul class="dropdown-menu dropdown-menu-end">
                                    @foreach ($available_locales as $locale_code => $locale_name)
                                        @if ($locale_code !== app()->getLocale())
                                            <li><a class="dropdown-item" href="{{ route('lang.switch', $locale_code) }}">{{ $locale_name }}</a></li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>

                            @guest
                                <li class="nav-item ms-lg-2 mt-3 mt-lg-0">
                                    <a class="btn btn-outline-primary rounded-pill w-100" href="{{ route('register') }}">{{ __('landing.register') }}</a>
                                </li>

                                <li class="nav-item ms-lg-2 mt-2 mt-lg-0 mb-2 mb-lg-0">
                                    <a class="btn btn-primary rounded-pill w-100" href="{{ route('login') }}">{{ __('landing.login') }}</a>
                                </li>
                            @endguest

                            @auth
                                <div class="dropdown">
                                    <button class="btn btn-primary rounded-pill dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Welcome, {{ Auth::user()->name }}!
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="{{ route('view.profile') }}">Profile</a></li>
                                        <li>
                                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="dropdown-item">Logout</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            @endauth
                        </ul>
                    </div>
                </div>
            </nav>
        </header>

        <section class="hero_section">
            <div class="container">
                <div class="row align-items-center justify-content-center py-lg-6">
                    <div class="col-lg-6 col-md-6">
                        <div class="welcome_card">
                            <div class="card_header_text">
                                <h1>{{ __('landing.hero_welcome') }}</h1>
                                <p class="sub_heading">{{ __('landing.hero_subtitle') }}</p>
                            </div>

                            @if ($errors->any())
                                <div class="alert alert-danger mb-3">
                                    <strong>{{ __('landing.error_warning') }}</strong>
                                    <ul class="mb-0" style="padding-left: 1.2rem;">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form id="rentalForm" action="{{ route('landing.search') }}" method="GET"> {{-- ubah di sini ya kalo mau searchnya beda nama routenya -> {{ route('vehicle.display') }} --}}
                                <div class="date_inputs">
                                    <div class="date_input_group">
                                        <span class="icon_calendar">
                                            <img src="{{ asset('landing_assets/images/Calender.png') }}" alt="Calendar Icon">
                                        </span>
                                        <input type="text" class="date_input_field" placeholder="{{ __('landing.start_date_placeholder') }}" id="startBookDate" name="start_book_date" value="{{ $search_data['start_book_date'] ?? '' }}" required>
                                    </div>
                                    <span class="arrow_separator">&#x2194;</span>
                                    <div class="date_input_group">
                                        <span class="icon_calendar">
                                            <img src="{{ asset('landing_assets/images/Calender.png') }}" alt="Calendar Icon">
                                        </span>
                                        <input type="text" class="date_input_field" placeholder="{{ __('landing.end_date_placeholder') }}" id="endBookDate" name="end_book_date" value="{{ $search_data['end_book_date'] ?? '' }}" required>
                                    </div>
                                </div>

                                <div class="vehicle_toggle_wrapper">
                                    <input type="checkbox" id="vehicleToggle" class="vehicle_toggle_checkbox"
                                           @if(isset($search_data['vehicle_type']) && $search_data['vehicle_type'] == 'car') checked @endif>
                                    <label for="vehicleToggle" class="vehicle_toggle_track">
                                        <span class="vehicle_toggle_thumb">
                                            <img src="{{ asset('landing_assets/images/Motor.png') }}" alt="Motorcycle" class="motorcycle_icon_img">
                                            <img src="{{ asset('landing_assets/images/Mobil.png') }}" alt="Car" class="car_icon_img">
                                        </span>
                                        <span class="track_text motorcycle_track_text">{{ __('landing.toggle_motorcycle') }}</span>
                                        <span class="track_text car_track_text">{{ __('landing.toggle_car') }}</span>
                                    </label>
                                    <input type="hidden" id="vehicleTypeInput" name="vehicle_type" value="{{ $search_data['vehicle_type'] ?? 'motorcycle' }}">
                                </div>
                                <button type="submit" class="btn btn-lg w-100" id="searchNowBtn" disabled>{{ __('landing.search_now_button') }}</button>
                            </form>
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-6 d-none d-md-block">
                        <div class="logo_display_area">
                            <div class="image_placeholder_area">
                                <div class="outer_circle">
                                    <div class="circle_image_placeholder">
                                        <img src="{{ asset('landing_assets/images/RentLab.png') }}" alt="RentLab Logo" class="rentlab_logo_in_circle">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="about_section pb-5">
            <div class="container text-center">
                <div class="row justify-content-center">
                    <div class="col-lg-12">
                        <h2 class="section_title">{{ __('landing.about_title') }}</h2>
                        <p class="lead text-muted">{{ __('landing.about_text') }}</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="features_section py-5">
            <div class="container text-center">
                <h2 class="section_title">{{ __('landing.features_title') }}</h2>
                <div class="row gy-4">
                    <div class="col-md-4">
                        <div class="feature_item">
                            <div class="icon_container"><img src="{{ asset('landing_assets/images/ArmadaBersih.png') }}" alt="Clean Fleet Icon"></div>
                            <h3>{{ __('landing.feature_1') }}</h3>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="feature_item">
                            <div class="icon_container"><img src="{{ asset('landing_assets/images/HargaKompetitif.png') }}" alt="Competitive Price Icon"></div>
                            <h3>{{ __('landing.feature_2') }}</h3>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="feature_item">
                            <div class="icon_container"><img src="{{ asset('landing_assets/images/PelayananRamah.png') }}" alt="Friendly Service Icon"></div>
                            <h3>{{ __('landing.feature_3') }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        @include('template.slider')

        <footer class="footer text-center py-4">
            <div class="container">
                <p class="mb-0">&copy; <script>document.write(new Date().getFullYear())</script> RentLab. {{ __('landing.footer_text') }}</p>
            </div>
        </footer>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script type="text/javascript" src="{{ asset('landing_assets/js/landing.js') }}?v={{ time() }}"></script>

    </body>
</html>
