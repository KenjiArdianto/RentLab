@extends('admin.master')

@section('content')
    
<div class="container-fluid justify-content-between align-items-center mb-4">
    <form action="{{ route('admin.users') }}" method="GET">
        <input name="search" class="form-control border-dark w-50 mx-auto my-2" placeholder="Search Users" aria-label="Search">
    </form>
</div> 

<div class="container mt-4">
    <form action="{{ route('admin.users.suspend') }}" method="post">
        <div class="d-flex justify-content-end mb-3">
            <button class="btn btn-danger px-4 py-2 rounded-3 fw-bold">Suspend</button>
        </div>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
            @foreach ($users as $user)
                <div class="col">
                    <div class="card shadow-sm p-3 d-flex flex-row align-items-center">
                        @if ($user->role != 'admin')
                            <img src="{{ asset($user->detail->profilePicture) }}" class="rounded-circle bg-secondary me-3" style="width: 60px; height: 60px;">
                        @else
                            <img src="{{ asset('assets\users\picture_profile_default.png') }}" class="rounded-circle bg-secondary me-3" style="width: 60px; height: 60px;">
                        @endif

                        <div>
                            <div class="fw-bold">Username: {{ $user->name }}</div>
                            <div>User ID: {{  $user->id }}</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </form>
</div>

<x-admin.feedback-modal/>

@endsection