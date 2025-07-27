@extends('admin.master')

@section('content')
    

<form action="{{ route('admin.users') }}" method="GET">
    <div class="container-fluid justify-content-between align-items-center mb-4">
        <input name="search" class="form-control border-dark w-50 mx-auto my-2" placeholder="Search Users" aria-label="Search">
    </div> 
    <div class="container-fluid d-flex justify-content-center mb-4">
        <select name="filter" class="form-select w-auto ms-3" onchange="this.form.submit()">
            <option value="" {{ request('filter') == null ? 'selected' : '' }}>All Users</option>
            <option value="active" {{ request('filter') == 'active' ? 'selected' : '' }}>Users</option>
            <option value="suspended" {{ request('filter') == 'suspended' ? 'selected' : '' }}>Suspended</option>
            <option value="deleted" {{ request('filter') == 'deleted' ? 'selected' : '' }}>Deleted</option>
        </select>

    </div>
</form>

<div class="container mt-4">
    @if (request('filter') == 'active')
            <form action="{{ route('admin.users.suspendSelected') }}" method="post">
        @elseif (request('filter') == 'suspended')
            <form action="{{ route('admin.users.unsuspendSelected') }}" method="post">
        @endif
    <form action="{{ route('admin.users.suspendSelected') }}" method="post">
        @csrf
        @if (request('filter') == 'active')
            <div class="d-flex justify-content-end mb-3">
                <button type="submit" class="btn btn-danger px-4 py-2 rounded-3 fw-bold">Suspend</button>
            </div>
        @elseif (request('filter') == 'suspended')
            <div class="d-flex justify-content-end mb-3">
                <button type="submit" class="btn btn-danger px-4 py-2 rounded-3 fw-bold">Unsuspend</button>
            </div>
        @endif
        
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
            @foreach ($users as $user)
                <div class="col">
                    @if (request('filter') == 'active' || request('filter') == 'suspended')
                        <div class="container-flex text-center" style="width: 23vw; height: 4vh" >
                            <input class="form-check-input" type="checkbox" name="selected[]" value="{{ $user->id }}" id="checkDefault" style="border: 1px solid black;!important; box-shadow: 0 0 3px rgba(0,0,0,0.3);!important">
                        </div>
                    @endif
                    <div class="card shadow-sm p-3 d-flex flex-row align-items-center" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#viewModal{{ $user->id }}">
                            <img src="{{  ($user->detail && $user->detail->profilePicture) ? asset($user->detail->profilePicture) : asset('assets/users/picture_profile_default.png') }}" class="rounded-circle bg-secondary me-3" style="width: 60px; height: 60px;">
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

@foreach ($users as $user)
    {{-- Modal --}}
    <div class="modal fade" id="viewModal{{ $user->id }}" tabindex="-1" aria-labelledby="viewModalLabel{{ $user->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="editCategoryModalLabel{{ $user->id }}">User - #{{ $user->id }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="d-flex justify-content-center gap-2 mb-4">
                    <img src="{{ $user->detail && $user->detail->profilePicture ? asset($user->detail->profilePicture) : asset('assets/users/picture_profile_default.png') }}" alt="Profile Picture" class="img-fluid border" style="max-height: 200px;">
                    <img src="{{ $user->detail && $user->detail->idcardPicture ? asset($user->detail->idcardPicture) : asset('assets/users/picture_id_default.jpg') }}" alt="ID Card" class="img-fluid border" style="max-height: 200px;">
                </div>
                <div class="text-start px-3">
                    <p><strong>Username</strong>: {{ $user->name }}</p>
                    <p><strong>Email</strong>: {{ $user->email }}</p>
                    <p><strong>First Name</strong>: {{ $user->detail ? $user->detail->fname : '-'}}</p>
                    <p><strong>Last Name</strong>: {{ $user->detail ? $user->detail->lname : '-' }}</p>
                    <p><strong>Phone Number</strong>: {{ $user->detail ? $user->detail->phoneNumber : '-' }}</p>
                    <p><strong>ID Card Number</strong>: {{ $user->detail ? $user->detail->idcardNumber : '-' }}</p>
                    <p><strong>Date of Birth</strong>: {{ $user->detail ? $user->detail->dateOfBirth : '-' }}</p>
                    <p><strong>Rating</strong>: {{ number_format($user->reviews->avg('rate'), 1) ?? number_format(0, 1) }} ({{ $user->reviews->count() }})</p>
                </div>
            </div>

            <div class="modal-footer">
                <a href="{{ route('admin.users.reviews', $user->id) }}" class="btn btn-info">View Reviews</a>
            
                @if (request('filter') == 'active')
                    <form action={{ route('admin.users.suspend', $user->id) }} method="post">
                @elseif (request('filter') == 'suspended')
                    <form action={{ route('admin.users.unsuspend', $user->id) }} method="post">
                @endif
                    @csrf
                    @if (request('filter') == 'active')
                        <button type="submit" class="btn btn-danger">Suspend</button>
                    @elseif (request('filter') == 'suspended')
                        <button type="submit" class="btn btn-danger">Unsuspend</button>
                    @endif
                </form>

                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    
            </div>


        </div>
    </div>
    </div>
@endforeach

<x-admin.feedback-modal/>

@endsection