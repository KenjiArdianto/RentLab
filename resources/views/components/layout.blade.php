<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RentLab</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
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

        .search-icon-label {
            cursor: pointer;
        }
    </style>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/litepicker/dist/css/litepicker.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
</head>
<body>

    <header class="sticky-top bg-light navbar-spacing">
        <nav class="navbar mt-0 mb-0 me-5 p-0 justify-content-end">
            <a class="navbar-brand d-flex align-items-center gap-1 p-0 m-0" href="#">
                <img src="{{ asset('build/assets/icons8-world-50.png') }}" width="12" height="12" alt="">
                <div class="ms-1 navbar-brand d-flex align-items-center p-0">
                    <p class="m-0 p-0 bold" style = "font-size: 9px;">Bahasa Indonesia</p>
                </div>
            </a>
        </nav>

        <nav class="navbar navbar-expand-lg navbar-light bg-light p-0">
            <div class="container-fluid ps-5">
                <div class="row w-100 align-items-center">

                    <div class="col-auto">
                        <a class="navbar-brand" href="/">RentLab</a>
                    </div>

                    <div class="col">
                        
                        <form action="{{ route('vehicle.catalog') }}" method="GET" class="d-flex justify-content-start align-items-center mt-0 mb-0" role="search">
                            <div class = "p-1 d-flex column justify-content-start align-items-center 
                            form-control border-primary ps-3 pe-3 w-100 rounded-4 search-container">
                                <label for="search-input" class="search-icon-label">
                                    <div type = "search" aria-label="Search">
                                        <img src="{{ asset('/navbar_assets/icons8-search-50.png') }}" alt="" width="20" height="20" class="">
                                    </div>
                                </label>

                                <div class = "container-fluid p-0">
                                    <input id="search-input" name="search" class="container-fluid border-0 search-input-inner pe-0 ps-3" type="search" placeholder="Cari kendaraan..." aria-label="Search" value="{{ request('search') }}">
                                </div>
                            </div>
                            
                        </form>
                    </div>

                    <div class="col-auto">
                        <ul class="navbar-nav flex-row">
                            <a class="navbar-brand ms-2 me-5 p-0 d-flex align-items-center" href="#">
                                <img src="{{ asset('/navbar_assets/reshot-icon-cart-CU9PKG8Z5X.svg') }}" width="30" height="30" alt="">
                            </a>
                            <a class="navbar-brand mx-2 ps-2 d-flex align-items-center" href="#">
                                <img src="{{ asset('/navbar_assets/reshot-icon-orders-SA9HJC27ED.svg') }}" width="30" height="30" alt="">
                            </a>
                            <a class="navbar-brand p-0 ms-5 me-0 d-flex align-items-center" href="#">
                                <img src="{{ asset('/navbar_assets/reshot-icon-profile-QX6KDSLJC5.svg') }}" width="50" height="50" alt="">
                            </a>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
    </header>


    <main class = "container-fluid p-0 m-0">
        {{ $slot }}
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/litepicker/dist/litepicker.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    {{-- Tambahan untuk Bahasa Indonesia --}}
    <script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>
</body>
</html>