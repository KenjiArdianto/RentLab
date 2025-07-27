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
    <nav class="navbar border-bottom border-dark position-relative">
        <div class="container-fluid d-flex align-items-center justify-content-between py-2">

            <!-- Left -->
            <div class="fw-bold fs-5">Welcome, Admin</div>

            <!-- Center (absolutely centered) -->
            <div class="position-absolute top-50 start-50 translate-middle">
                <a href="{{ route('admin.index') }}" class="btn btn-primary">Home</a>
            </div>

            <!-- Right (visible RentLab) -->
            <div class="fw-bold fs-5 text-end">RentLab</div>
        </div>
    </nav>





    <div>
        @yield('content')
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>