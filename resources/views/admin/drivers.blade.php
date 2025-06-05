@extends('admin.master')

@section('content')
    {{-- <div class="container-flex text-center my-4">
        <div class="row">
            <div class="col">
                <div class="text"></div>
                <h5><strong>LogID</strong></h5>
            </div>
            <div class="col">
                <h5><strong>Username</strong></h5>
            </div>
            <div class="col">
                <h5><strong>UserID</strong></h5>
            </div>
            <div class="col">
                <h5><strong>Action</strong></h5>
            </div>
            <div class="col">
                <h5><strong>Date Time</strong></h5>
            </div>
        </div>

        @php
    $logs = [
        [
            'id' => 101,
            'username' => 'admin',
            'user_id' => 1,
            'action' => 'Login',
            'created_at' => '2022-12-12 12:12:12',
        ],
        [
            'id' => 102,
            'username' => 'admin',
            'user_id' => 1,
            'action' => 'Login',
            'created_at' => '2022-12-12 12:12:12',
        ]
    ];
@endphp

@foreach ($logs as $log)
    <div class="row">
        <div class="col">
            <p>{{ $log['id'] }}</p>
        </div>
        <div class="col">
            <p>{{ $log['username'] }}</p>
        </div>
        <div class="col">
            <p>{{ $log['user_id'] }}</p>
        </div>
        <div class="col">
            <p>{{ $log['action'] }}</p>
        </div>
        <div class="col">
            <p>{{ $log['created_at'] }}</p>
        </div>
    </div>
@endforeach

    </div> --}}

    <div class="container-fluid justify-content-between align-items-center">
        <form action="{{ route('admin.drivers.search') }}" method="GET">
            <input name="search" class="form-control border-dark w-50 mx-auto my-2" placeholder="Format: Attribute=Value" aria-label="Search">
            
        </form>
    </div>  


    <div class="mx-5 my-5">
        @foreach ($drivers as $driver)
            <p>{{ $driver->name }}</p>
        @endforeach
    </div>

    <nav aria-label="Page navigation example" class="d-flex justify-content-center align-items-center">
        <ul class="pagination pagination-lg">
            <li class="page-item text-center" style="width: 15vw;">
                <a class="page-link" href="?page={{ $drivers->currentPage() - 1 }}" aria-label="Previous">
                    Previous
                </a>
            </li>
            <input type="text" class="form-control rounded-0" value="{{ $drivers->currentPage() }}">
            <li class="page-item text-center" style="width: 15vw;">
                
            </li>
            <div class="page-item text-center border border-dark">
                <a class="page-link" href="?page={{ $drivers->currentPage() + 1 }}" aria-label="Next">
                    Next
                </a>
            </div>
        </ul>
    </nav>

@endsection










{{-- @for ($i = 1; $i <= ceil($drivers->total() / $drivers->perPage()); $i++)
                <div>
                    <li class="page-item"><a class="page-link" href="?page={{ $i }}">{{ $i }}</a></li>
                </div>
            @endfor --}}