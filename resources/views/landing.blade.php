<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Rent Lab - Easy Vehicle Rentals</title>

        <link rel="stylesheet" href="{{ asset('build/assets/CSS/landing.css') }}">

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    </head>
    <body>

        {{-- ================================================================= --}}
        {{--                            HEADER                                 --}}
        {{-- ================================================================= --}}
        
        <header>
            <nav class="navbar navbar-expand-lg bg-white fixed-top border-bottom">
                <div class="container-fluid py-2 px-lg-5">
                    {{-- Application Brand/Logo --}}
                    <a class="navbar-brand fw-bold" style="font-family: 'Poppins', sans-serif; font-size: 2rem; color: #0D2A4E;" href="{{ route('landing.index') }}">RENT LAB</a>
                    
                    {{-- Toggler button for mobile view --}}
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    {{-- Collapsible Navbar Content --}}
                    <div class="collapse navbar-collapse" id="mainNavbar">
                        {{-- Authentication buttons moved to the right using margin-start: auto --}}
                        <div class="navbar-nav ms-auto align-items-center">
                            <a class="btn btn-outline-primary rounded-pill px-4 me-lg-2 mb-2 mb-lg-0" href="#">Register</a> {{-- ubah di sini ya kalo register nya beda nama routenya -> href="{{ route('register') }}" --}}
                            <a class="btn btn-primary rounded-pill px-4" href="#">Login</a> {{-- ubah di sini ya kalo login nya beda nama routenya -> href="{{ route('login') }}"--}}
                        </div>
                    </div>
                </div>
            </nav>
        </header>

        <section class="hero_section">
            <div class="container">
                <div class="row align-items-center justify-content-center py-lg-5">
                    <div class="col-lg-6 col-md-10">
                        <div class="welcome_card">
                            <div class="card_header_text">
                                <h1>Welcome</h1>
                                <p class="sub_heading">What would you like to rent today?</p>
                            </div>

                            @if ($errors->any())
                                <div class="alert alert-danger mb-3">
                                    <strong>Whoops! Something went wrong:</strong>
                                    <ul class="mb-0" style="padding-left: 1.2rem;">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form id="rentalForm" action="{{ route('vehicle.display') }}" method="GET"> {{-- ubah di sini ya kalo mau searchnya beda nama routenya -> {{ route('vehicle.display') }} --}}
                                <div class="date_inputs">
                                    <div class="date_input_group">
                                        <span class="icon_calendar">
                                            <img src="{{ asset('build/assets/images/Calender.png') }}" alt="Calendar Icon">
                                        </span>
                                        {{-- The 'name' attribute now matches the migration column --}}
                                        <input type="text" class="date_input_field" placeholder="Start Date" id="startBookDate" name="start_book_date" value="{{ $search_data['start_book_date'] ?? '' }}" required>
                                    </div>
                                    <span class="arrow_separator">&#x2194;</span>
                                    <div class="date_input_group">
                                        <span class="icon_calendar">
                                            <img src="{{ asset('build/assets/images/Calender.png') }}" alt="Calendar Icon">
                                        </span>
                                        {{-- The 'name' attribute now matches the migration column --}}
                                        <input type="text" class="date_input_field" placeholder="End Date" id="endBookDate" name="end_book_date" value="{{ $search_data['end_book_date'] ?? '' }}" required>
                                    </div>
                                </div>

                                <div class="vehicle_toggle_wrapper">
                                    <input type="checkbox" id="vehicleToggle" class="vehicle_toggle_checkbox" 
                                           @if(isset($search_data['vehicle_type']) && $search_data['vehicle_type'] == 'car') checked @endif>
                                    <label for="vehicleToggle" class="vehicle_toggle_track">
                                        <span class="vehicle_toggle_thumb">
                                            <img src="{{ asset('build/assets/images/Motor.png') }}" alt="Motorcycle" class="motorcycle_icon_img">
                                            <img src="{{ asset('build/assets/images/Mobil.png') }}" alt="Car" class="car_icon_img">
                                        </span>
                                        <span class="track_text motorcycle_track_text">Motorcycle</span>
                                        <span class="track_text car_track_text">Car</span>
                                    </label>
                                    {{-- This input sends the vehicle type ('car' or 'motorcycle') for searching --}}
                                    <input type="hidden" id="vehicleTypeInput" name="vehicle_type" value="{{ $search_data['vehicle_type'] ?? 'motorcycle' }}">
                                </div>
                                <button type="submit" class="btn btn-lg w-100" id="searchNowBtn" disabled>Search Now</button>
                            </form>
                        </div>
                    </div>

                    <div class="col-lg-6 d-none d-lg-block">
                        <div class="logo_display_area">
                            <div class="image_placeholder_area">
                                <div class="outer_circle">
                                    <div class="circle_image_placeholder">
                                        <img src="{{ asset('build/assets/images/RentLab.png') }}" alt="RentLab Logo" class="rentlab_logo_in_circle">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="about_section py-5">
            <div class="container text-center"> 
                <div class="row justify-content-center">
                    <div class="col-lg-12"> 
                        <h2 class="section_title">What is Rent Lab?</h2>
                        <p class="lead text-muted">Rent Lab provides modern mobility solutions through an integrated digital platform. We are committed to providing a quality fleet of professionally maintained vehicles, with transparent pricing principles and supported by responsive customer service, to ensure a safe, comfortable, and efficient rental experience for every customer.</p> 
                    </div>
                </div> 
            </div>
        </section>

        <section class="features_section py-5">
            <div class="container text-center">
                <h2 class="section_title">Why Rent Lab?</h2>
                <div class="row gy-4">
                    <div class="col-md-4">
                        <div class="feature_item">
                            <div class="icon_container"><img src="{{ asset('build/assets/images/ArmadaBersih.png') }}" alt="Clean Fleet Icon"></div>
                            <h3>Clean and Well-Maintained Fleet</h3>
                        </div>
                    </div> 
                    <div class="col-md-4">
                        <div class="feature_item">
                            <div class="icon_container"><img src="{{ asset('build/assets/images/HargaKompetitif.png') }}" alt="Competitive Price Icon"></div>
                            <h3>Transparent and Competitive Pricing</h3>
                        </div>
                    </div> 
                    <div class="col-md-4">
                        <div class="feature_item">
                            <div class="icon_container"><img src="{{ asset('build/assets/images/PelayananRamah.png') }}" alt="Friendly Service Icon"></div>
                            <h3>Friendly and Responsive Service</h3>
                        </div>
                    </div>
                </div>
            </div> 
        </section>
        
        @include('template.slider')

        <footer class="footer text-center py-4">
            <div class="container">
                <p class="mb-0">&copy; <script>document.write(new Date().getFullYear())</script> Rent Lab. All Rights Reserved.</p>
            </div>
        </footer>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script type="text/javascript" src="{{ asset('build/assets/js/landing.js') }}"></script>
    
        <script>
            // Wait until the entire HTML document is fully loaded
            document.addEventListener('DOMContentLoaded', function() {

                function checkDates() {
                    const startDateValue = document.getElementById('startBookDate').value;
                    const endDateValue = document.getElementById('endBookDate').value;
                    const searchButton = document.getElementById('searchNowBtn');
                    
                    if (startDateValue && endDateValue) {
                        searchButton.removeAttribute('disabled');
                    } else {
                        searchButton.setAttribute('disabled', 'true');
                    }
                }

                // Simpan instance flatpickr End Date ke dalam variabel
                const endDatePicker = flatpickr("#endBookDate", {
                    altInput: true,
                    altFormat: "d/m/Y",
                    dateFormat: "Y-m-d",
                    minDate: "today",
                    onChange: function(selectedDates, dateStr, instance) {
                        checkDates(); // Cek tanggal setiap kali ada perubahan
                    }
                });

                // Konfigurasi Flatpickr untuk Start Date, dengan logika tambahan
                flatpickr("#startBookDate", {
                    altInput: true,
                    altFormat: "d/m/Y",
                    dateFormat: "Y-m-d",
                    minDate: "today",
                    onChange: function(selectedDates, dateStr, instance) {
                        checkDates(); // Tetap jalankan fungsi pengecekan tanggal

                        // Jika ada tanggal yang dipilih di Start Date
                        if (selectedDates[0]) {
                            // atur tanggal minimum di End Date menjadi tanggal tersebut.
                            endDatePicker.set('minDate', selectedDates[0]);

                            // Jika tanggal End Date yang sudah terpilih lebih kecil dari Start Date baru, hapus isinya.
                            if (endDatePicker.selectedDates[0] < selectedDates[0]) {
                                endDatePicker.clear();
                            }
                        }
                    }
                });


                // Logic for the motorcycle/car toggle button
                const vehicleToggle = document.getElementById('vehicleToggle');
                const vehicleTypeInput = document.getElementById('vehicleTypeInput');

                vehicleToggle.addEventListener('change', function() {
                    if (this.checked) {
                        vehicleTypeInput.value = 'car';
                    } else {
                        vehicleTypeInput.value = 'motorcycle';
                    }
                });

                // Call checkDates on initial load, in case of old values being present
                checkDates();
            });
        </script>

    </body>
</html>