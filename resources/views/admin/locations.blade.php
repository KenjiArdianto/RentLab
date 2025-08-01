@extends('admin.master')


@section('content')

<div class="container-fluid justify-content-between align-items-center mb-4">
    <form action="{{ route('admin.locations') }}" method="GET">
        <input name="search" class="form-control border-dark w-50 mx-auto my-2" placeholder="{{ __('admin_search_hints.locations') }}" aria-label="Search">
    </form>
</div> 

<div class="container mt-4">
    <h3 class="mb-3">{{ __('admin_tables.locations') }}</h3>

    <div class="border rounded p-2 mb-3 d-flex justify-content-between align-items-center">
        <div style="width: 40px;">
            <strong>+</strong>
        </div>
        <form action="{{ route('admin.locations.store') }}" method="POST" class="d-flex flex-fill mx-2">
            @csrf
            <input type="text" name="location" class="form-control form-control-sm me-2" placeholder="Enter new location">
            <button type="submit" class="btn btn-sm btn-primary">{{ __('admin_buttons.add') }}</button>
        </form>
    </div>

    @foreach ($locations as $location)
        <div class="border rounded p-2 mb-2 d-flex justify-content-between align-items-center">

            <div style="width: 40px;">
                <strong>{{ str_pad($location->id, 2, '0', STR_PAD_LEFT) }}</strong>
            </div>

            <form action="{{ route('admin.locations.update', $location->id) }}" method="POST" class="d-flex flex-fill mx-2">
                @csrf
                <input type="text" name="location" class="form-control form-control-sm me-2" value="{{ $location->location }}">
                <button type="submit" class="btn btn-sm btn-success">{{ __('admin_buttons.apply') }}</button>
            </form>

            <form action="{{ route('admin.locations.destroy', $location->id) }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" class="btn btn-sm btn-danger">{{ __('admin_buttons.delete') }}</button>
            </form>

        </div>
    @endforeach
</div>

<div class="container d-flex justify-content-center my-4">
    {{ $locations->onEachSide(5)->links('pagination::bootstrap-5') }}
</div>

@endsection