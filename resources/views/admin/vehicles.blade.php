@extends('admin.master')

@section('content')
    @foreach ($v as $vehicle)
        @foreach ($vehicle->vehicleCategories as $category)
            <p>{{ $category }}</p>    
        @endforeach
    @endforeach
@endsection