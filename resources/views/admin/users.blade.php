@extends('admin.master')

@section('content')
    
@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>ini error {{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.users') }}" method="GET">
    <div class="container-fluid justify-content-between align-items-center mb-4">
        <input name="search" class="form-control border-dark w-50 mx-auto my-2" placeholder="{{ __('admin_search_hints.users') }}" aria-label="Search">
    </div> 
    <div class="container-fluid d-flex justify-content-center mb-4">
        <select name="filter" class="form-select w-auto ms-3" onchange="this.form.submit()">
            <option value="" {{ request('filter') == null ? 'selected' : '' }}>{{ __('admin_users.all_user') }}</option>
            <option value="active" {{ request('filter') == 'active' ? 'selected' : '' }}>{{ __('admin_tables.users') }}</option>
            <option value="suspended" {{ request('filter') == 'suspended' ? 'selected' : '' }}>{{ __('admin_users.suspended') }}</option>
            <option value="deleted" {{ request('filter') == 'deleted' ? 'selected' : '' }}>{{ __('admin_users.deleted') }}</option>
        </select>

    </div>
</form>

@php
    use Illuminate\Support\Str;
@endphp


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
                <button type="submit" class="btn btn-danger px-4 py-2 rounded-3 fw-bold">{{ __('admin_users.suspend') }}</button>
            </div>
        @elseif (request('filter') == 'suspended')
            <div class="d-flex justify-content-end mb-3">
                <button type="submit" class="btn btn-danger px-4 py-2 rounded-3 fw-bold">{{ __('admin_users.unsuspend') }}</button>
            </div>
        @endif
        
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
            @foreach ($users as $user)
                <div class="col">
                    <div class="d-flex align-items-center gap-2">
                        @if (request('filter') == 'active' || request('filter') == 'suspended')
                            <input class="form-check-input mt-0" type="checkbox" name="selected[]" value="{{ $user->id }}" style="border: 1px solid black; box-shadow: 0 0 3px rgba(0,0,0,0.3);">
                        @endif

                        <div class="card flex-grow-1 shadow-sm p-3 d-flex flex-row align-items-center" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#viewModal{{ $user->id }}">
                            <img src="{{ ($user->detail && $user->detail->profilePicture) ? asset($user->detail->profilePicture) : asset('assets/users/picture_profile_default.png') }}" class="rounded-circle bg-secondary me-3" style="width: 60px; height: 60px;">
                            <div>
                                <div class="fw-bold">{{ __('admin_tables.username') }}: {{ \Illuminate\Support\Str::limit($user->name, 20, '...') }}</div>
                                <div>{{ __('admin_tables.user_id') }}: {{ $user->id }}</div>
                            </div>
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
                <h5 class="modal-title" id="editCategoryModalLabel{{ $user->id }}">{{ __('admin_tables.users') }} - #{{ $user->id }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="d-flex justify-content-center gap-2 mb-4">
                    <img src="{{ $user->detail && $user->detail->profilePicture ? asset($user->detail->profilePicture) : asset('assets/users/picture_profile_default.png') }}" alt="Profile Picture" class="img-fluid border" style="max-height: 15vh;">
                    <img src="{{ $user->detail && $user->detail->idcardPicture ? asset($user->detail->idcardPicture) : asset('assets/users/picture_id_default.jpg') }}" alt="ID Card" class="img-fluid border" style="max-height: 15vh;">
                </div>
                <div class="text-start px-3">
                    <p><strong>{{ __('admin_tables.username') }}</strong>: {{ $user->name }}</p>
                    <p><strong>{{ __('admin_tables.email') }}</strong>: {{ $user->email }}</p>
                    <p><strong>{{ __('admin_tables.fname') }}</strong>: {{ $user->detail ? $user->detail->fname : '-'}}</p>
                    <p><strong>{{ __('admin_tables.lname') }}</strong>: {{ $user->detail ? $user->detail->lname : '-' }}</p>
                    <p><strong>{{ __('admin_tables.pnumber') }}</strong>: {{ $user->detail ? $user->detail->phoneNumber : '-' }}</p>
                    <p><strong>{{ __('admin_tables.idcard') }}</strong>: {{ $user->detail ? $user->detail->idcardNumber : '-' }}</p>
                    <p><strong>{{ __('admin_tables.dob') }}</strong>: {{ $user->detail ? $user->detail->dateOfBirth : '-' }}</p>
                    <p><strong>{{ __('admin_tables.rating') }}</strong>: {{ number_format($user->reviews->avg('rate'), 1) ?? number_format(0, 1) }} ({{ $user->reviews->count() }})</p>
                    @if ( $user->suspended_at)
                        <p><strong>{{ __('admin_users.suspended_at') }}</strong>: {{ $user->suspended_at }}</p>
                    @else 
                        @if ($user->deleted_at)
                            <p><strong>{{ __('admin_users.deleted_at') }}</strong>: {{  $user->deleted_at }}</p>
                        @endif
                    @endif
                </div>
            </div>

            <div class="modal-footer">
                <a href="{{ route('admin.users.reviews', $user->id) }}" class="btn btn-info">{{ __('admin_users.view_review') }}</a>
            
                @if (request('filter') == 'active')
                    <form action={{ route('admin.users.suspend', $user->id) }} method="post">
                @elseif (request('filter') == 'suspended')
                    <form action={{ route('admin.users.unsuspend', $user->id) }} method="post">
                @endif
                    @csrf
                    @if (request('filter') == 'active')
                        <button type="submit" class="btn btn-danger">{{ __('admin_users.suspend') }}</button>
                    @elseif (request('filter') == 'suspended')
                        <button type="submit" class="btn btn-danger">{{ __('admin_users.unsuspend') }}</button>
                    @endif
                </form>

                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('admin_users.close') }}</button>
                    
            </div>


        </div>
    </div>
    </div>
@endforeach

<x-admin.feedback-modal/>

@endsection