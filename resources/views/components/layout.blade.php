<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RentLab</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

    <header class="sticky-top bg-light navbar-spacing">
        <nav class="navbar mt-0 mb-0 me-5 p-0 justify-content-end">
            <a class="navbar-brand d-flex align-items-center gap-1 p-0 m-0" href="#">
                <img src="{{ asset('build/assets/header_assets/icons8-world-50.png') }}" width="12" height="12" alt="">
                <div class="ms-1 navbar-brand d-flex align-items-center p-0">
                    <p class="m-0 p-0 bold" style = "font-size: 9px;">Bahasa Indonesia</p>
                </div>
            </a>
        </nav>

        <nav class="navbar navbar-expand-lg navbar-light bg-light p-0">
            <div class="container-fluid ps-5">
                <div class="row w-100 align-items-center">
                    <!-- Brand -->
                    <div class="col-auto">
                        <a class="navbar-brand" href="/">RentLab</a>
                    </div>

                    <!-- Search Form (tengah) -->
                    <div class="col text-center">
                        <form class="d-flex m-2 justify-content-center align-items-center" role="search">
                            <input class="form-control border-primary ps-3 w-100 rounded-start-pill" type="search" placeholder="Search" aria-label="Search">
                            <button class="btn btn-outline-success pe-4 ps-3 rounded-end-pill" type="submit">Search</button>
                        </form>
                    </div>

                    <!-- Menu (kanan) -->
                    <div class="col-auto">
                        <ul class="navbar-nav flex-row">
                            <a class="navbar-brand ms-2 me-5 p-0 d-flex align-items-center" href="#">
                                <img src="{{ asset('build/assets/header_assets/reshot-icon-cart-CU9PKG8Z5X.svg') }}" width="30" height="30" alt="">
                            </a>
                            <a class="navbar-brand mx-2 ps-2 d-flex align-items-center" href="#">
                                <img src="{{ asset('build/assets/header_assets/reshot-icon-orders-SA9HJC27ED.svg') }}" width="30" height="30" alt="">
                            </a>
                            <a class="navbar-brand p-0 ms-5 me-0 d-flex align-items-center" href="#">
                                <img src="{{ asset('build/assets/header_assets/reshot-icon-profile-QX6KDSLJC5.svg') }}" width="50" height="50" alt="">
                            </a>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
    </header>


    <main class = "container">
        {{ $slot }}
    </main>

</body>
</html>