@extends('admin.master')


@section('content')

<div class="container-fluid justify-content-between align-items-center mb-4">
    <form action="{{ route('admin.vehicle-categories') }}" method="GET">
        <input name="search" class="form-control border-dark w-50 mx-auto my-2" placeholder="{{ __('admin_search_hints.vehicle_categories') }}" aria-label="Search" value="{{ request('search') }}">
        
    </form>
</div> 

<div class="container mt-4">
    <h3 class="mb-3">{{ __('admin_tables.vehicle_categories') }}</h3>

    <div class="border rounded p-2 mb-3 d-flex justify-content-between align-items-center">
        <div style="width: 40px;">
            <strong>+</strong>
        </div>
        <form action="{{ route('admin.vehicle-categories.store') }}" method="POST" class="d-flex flex-fill mx-2">
            @csrf
            <input type="text" name="category" class="form-control form-control-sm me-2" placeholder="Enter new vehicle category">
            <button type="submit" class="btn btn-sm btn-primary">{{ __('admin_buttons.add') }}</button>
        </form>
    </div>

    @foreach ($vehicleCategories as $vehicleCategory)
        <div class="border rounded p-2 mb-2 d-flex justify-content-between align-items-center">

            <div style="width: 40px;">
                <strong>{{ str_pad($vehicleCategory->id, 2, '0', STR_PAD_LEFT) }}</strong>
            </div>

            <form action="{{ route('admin.vehicle-categories.update', $vehicleCategory->id) }}" method="POST" class="d-flex flex-fill mx-2">
                @csrf
                <input type="text" name="category" class="form-control form-control-sm me-2" value="{{ $vehicleCategory->category }}">
                <button type="submit" class="btn btn-sm btn-success">{{ __('admin_buttons.apply') }}</button>
            </form>

            <form action="{{ route('admin.vehicle-categories.destroy', $vehicleCategory->id) }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" class="btn btn-sm btn-danger">{{ __('admin_buttons.delete') }}</button>
            </form>

        </div>
    @endforeach
</div>

<div class="container d-flex justify-content-center my-4">
    {{ $vehicleCategories->onEachSide(5)->links('pagination::bootstrap-5') }}
</div>

@endsection