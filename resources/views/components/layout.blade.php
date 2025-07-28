<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RentLab</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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

        .search-icon-label {
            cursor: pointer;
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
</head>
<body>

    <header class="sticky-top bg-light shadow-sm">
        <!-- Top bar for language selection -->
        <nav class="navbar mt-0 mb-0 me-lg-0 p-0 justify-content-end d-none d-lg-flex">
            <div class="container-fluid justify-content-end">
                <a class="nav-link py-1 px-3 small" href="#">@lang('app.nav.faq')</a>
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
                <!-- Flex container for responsive behavior -->
                <div class="d-flex justify-content-between align-items-center w-100 pt-0">

                    <!-- Brand: Hidden on small screens, visible on large -->
                    <a class="navbar-brand fs-3 fw-bold mx-3 d-none d-lg-block" href="/">RentLab</a>

                    <!-- Desktop Search Form: Visible on LG screens and up -->
                    <div class="flex-grow-1 mx-lg-4 d-none d-lg-flex">
                        <form class="w-100" role="search">
                            <div class="p-1 d-flex flex-row justify-content-start align-items-center form-control border-primary ps-3 pe-3 w-100 rounded-pill search-container">
                                <label for="search-input-desktop" class="search-icon-label"><i class="bi bi-search"></i></label>
                                <div class="container-fluid p-0">
                                    <input id="search-input-desktop" class="container-fluid border-0 search-input-inner pe-0 ps-3 bg-transparent" type="search" placeholder="Cari produk atau layanan..." aria-label="Search">
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Mobile Search Form: Visible below LG screens -->
                    <div class="flex-grow-1 me-2 d-lg-none">
                        <form class="w-100" role="search">
                             <div class="p-1 d-flex flex-row justify-content-start align-items-center form-control border-primary ps-3 pe-3 w-100 rounded-pill search-container">
                                <label for="search-input-mobile" class="search-icon-label"><i class="bi bi-search"></i></label>
                                <div class="container-fluid p-0">
                                    <input id="search-input-mobile" class="container-fluid border-0 search-input-inner pe-0 ps-3 bg-transparent" type="search" placeholder="Cari..." aria-label="Search">
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Right-side Icons -->
                    <ul class="navbar-nav flex-row align-items-center">
                        <!-- Cart Icon (Visible on all sizes) -->
                        <li class="nav-item">
                            <a class="nav-link p-2 px-4" href="#" title="Keranjang">
                                <i class="bi bi-cart fs-4"></i>
                            </a>
                        </li>
                        <!-- Desktop-only Icons -->
                        <li class="nav-item d-none d-lg-inline-block">
                            <a class="nav-link p-2 px-4" href="#" title="Transaksi">
                                <i class="bi bi-receipt fs-4"></i>
                            </a>
                        </li>
                        <li class="nav-item d-none d-lg-inline-block">
                            <a class="nav-link p-2 px-4" href="#" title="Akun">
                                <i class="bi bi-person-circle fs-4"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="">
        <!-- The content from your Blade templates will be injected here -->
        {{ $slot }}
    </main>

    <!-- Bottom Navigation Bar (Mobile Only) -->
    <nav class="navbar fixed-bottom navbar-light bg-light d-lg-none bottom-nav p-0">
        <div class="container-fluid">
            <ul class="navbar-nav d-flex flex-row justify-content-around w-100">
                <li class="nav-item">
                    <a class="nav-link d-flex flex-column align-items-center active" href="/home" title="Home">
                        <i class="bi bi-house-door-fill"></i>
                        <span>Home</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex flex-column align-items-center" href="#" title="Transaksi">
                        <i class="bi bi-receipt"></i>
                        <span>Transaksi</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex flex-column align-items-center" href="#" title="Akun">
                        <i class="bi bi-person"></i>
                        <span>Akun</span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
