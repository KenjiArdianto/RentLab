@extends('admin.master')


@section('content')

<div class="container-fluid justify-content-between align-items-center mb-4">
    <form action="{{ route('admin.vehicle-categories') }}" method="GET">
        <input name="search" class="form-control border-dark w-50 mx-auto my-2" placeholder="Search Vehicle Categories" aria-label="Search">
        
    </form>
</div> 

<div class="container mt-4">
    <h3 class="mb-3">Vehicle Categories</h3>

    <div class="border rounded p-2 mb-3 d-flex justify-content-between align-items-center">
        <div style="width: 40px;">
            <strong>+</strong>
        </div>
        <form action="{{ route('admin.vehicle-categories.store') }}" method="POST" class="d-flex flex-fill mx-2">
            @csrf
            <input type="text" name="category" class="form-control form-control-sm me-2" placeholder="Enter new vehicle category">
            <button type="submit" class="btn btn-sm btn-primary">Add</button>
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
                <button type="submit" class="btn btn-sm btn-success">Apply</button>
            </form>

            <form action="{{ route('admin.vehicle-categories.destroy', $vehicleCategory->id) }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
            </form>

        </div>
    @endforeach
</div>

<div class="container">
    {{ $vehicleCategories->onEachSide(5)->links('pagination::bootstrap-5') }}
</div>


<x-admin.feedback-modal/>


@endsection