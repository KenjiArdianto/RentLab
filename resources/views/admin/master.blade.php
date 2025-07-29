<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>admin.master</title>
    @vite(['resources/js/app.js', 'resources/sass/app.scss'])
    <style>
        @media (max-width: 575.98px) {
            .navbar-text-lg {
                font-size: 1rem !important;
            }

            .navbar-center,
            .navbar-language {
                position: static !important;
                transform: none !important;
                margin-left: 0 !important;
                text-align: center;
                width: 100%;
                margin-top: 0.5rem;
            }

            .navbar-language select {
                width: 100%;
            }

            .navbar > .container-fluid {
                flex-direction: column;
                align-items: stretch;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar border-bottom border-dark position-relative">
        <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap py-2">

            <!-- Left -->
            <div class="fw-bold fs-5 navbar-text-lg">{{ __('admin_navbar.welcome') }}</div>

            <!-- Center (absolutely centered on desktop) -->
            <div class="position-absolute top-50 start-50 translate-middle navbar-center">
                <a href="{{ route('admin.index') }}" class="btn btn-primary">{{  __('admin_navbar.home') }}</a>
            </div>

            <!-- Language Dropdown Selector (Bootstrap-styled, right of Home button) -->
            <div class="position-absolute top-50 start-50 translate-middle navbar-language" style="margin-left: 120px;">
                <form method="GET" action="" id="langForm">
                    <select name="locale" class="form-select form-select-sm" onchange="changeLanguage(this)">
                        <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>English</option>
                        <option value="id" {{ app()->getLocale() == 'id' ? 'selected' : '' }}>Indonesia</option>
                    </select>
                </form>
            </div>

            <script>
                function changeLanguage(select) {
                    const lang = select.value;
                    window.location.href = `{{ url('lang') }}/${lang}`;
                }
            </script>

            <!-- Right -->
            <div class="fw-bold fs-5 text-end navbar-text-lg">RentLab</div>
        </div>
    </nav>

    <div>
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>
