<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>admin.master</title>
    @vite(['resources/js/app.js', 'resources/sass/app.scss'])
</head>
<body>
    <nav class="navbar navbar-expand-lg border-bottom border-dark">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <div class="navbar-text fs-4 fw-bold px-4">
                Welcome, Admin
            </div>
            <ul class="navbar-nav flex-row position-absolute top-50 start-50 translate-middle">
                <li class="nav-item mx-2 btn-lg">
                    <a 
                    href="{{route('admin.logs')}}" 
                    role="button"
                    class="btn {{ request()->routeIs('admin.logs*') ? 'btn-primary' : 'btn-outline-primary'}}" 
                    aria-current="{{ request()->routeIs('admin.logs*') ? 'page' : ''}}">
                    Logs
                    </a>
                </li>
                <li class="nav-item mx-2">
                    <a 
                    href="{{route('admin.users')}}" 
                    role="button"
                    class="btn {{ request()->routeIs('admin.users*') ? 'btn-primary' : 'btn-outline-primary'}}" 
                    aria-current="{{ request()->routeIs('admin.users*') ? 'page' : ''}}">
                    Users
                    </a>
                </li>
                <li class="nav-item mx-2">
                    <a 
                    href="{{route('admin.drivers')}}" 
                    role="button"
                    class="btn {{ request()->routeIs('admin.drivers*') ? 'btn-primary' : 'btn-outline-primary'}}" 
                    aria-current="{{ request()->routeIs('admin.drivers*') ? 'page' : ''}}">
                    Drivers
                    </a>
                </li>   
                <li class="nav-item mx-2">
                    <a 
                    href="{{route('admin.transactions')}}" 
                    role="button"
                    class="btn {{ request()->routeIs('admin.transactions*') ? 'btn-primary' : 'btn-outline-primary'}}" 
                    aria-current="{{ request()->routeIs('admin.transactions*') ? 'page' : ''}}">
                    Transactions
                    </a>
                </li>
                <li class="nav-item mx-2">
                    <a 
                    href="{{route('admin.vehicles')}}" 
                    role="button"
                    class="btn {{ request()->routeIs('admin.vehicles*') ? 'btn-primary' : 'btn-outline-primary'}}" 
                    aria-current="{{ request()->routeIs('admin.vehicles*') ? 'page' : ''}}">
                    Vehicles
                    </a>
                </li>
                <li class="nav-item mx-2">
                    <a 
                    href="{{route('admin.reviews')}}" 
                    role="button"
                    class="btn {{ request()->routeIs('admin.reviews*') ? 'btn-primary' : 'btn-outline-primary'}}" 
                    aria-current="{{ request()->routeIs('admin.reviews*') ? 'page' : ''}}">
                    Reviews
                    </a>
                </li>
            </ul>
            <div class="navbar-text fs-4 fw-bold px-4">
                RentLab
            </div>
        </div>
    </nav>

    <div>
        @yield('content')
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>