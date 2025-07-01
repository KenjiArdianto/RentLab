@extends('admin.master')

@section('content')

    <div class="container-fluid justify-content-between align-items-center">
        <form action="{{ route('admin.drivers.search') }}" method="GET">
            <input name="search" class="form-control border-dark w-50 mx-auto my-2" placeholder="Format: Attribute=Value" aria-label="Search">
            
        </form>
    </div>  

    <form action="{{ route('admin.drivers.destroy') }}" method="POST">
        @csrf
        @method('DELETE')

        <div class="mt-4 d-flex justify-content-center">
            <button type="submit" class="btn btn-danger">Delete Selected</button>
        </div>

        <div class="container-flex m-4">
            <div class="row row-cols-1 row-cols-lg-4 g-4">
                <div class="col">
                    <div class="card text-center d-flex align-items-center justify-content-center" style="width: 23vw; height: 60vh; font-size: 48px; cursor: pointer; cursor: pointer; border: 1.5px dashed black;!important;">
                        +
                    </div>
                </div>
                @foreach ($drivers as $driver)
                    <div class="col">
                        <div class="card" style="width: 23vw; height: 60vh; cursor: pointer; border: 1px solid black;!important;">
                            <div class="container-flex text-center " >
                                <input class="form-check-input" type="checkbox" name="selected[]" value="{{ $driver->id }}" id="checkDefault" style="border: 1px solid black;!important; box-shadow: 0 0 3px rgba(0,0,0,0.3);!important">
                            </div>
                            {{-- <img src="{{ asset('storage/' . $driver->image) }}" alt="Driver Image" class="card-img-top"> --}}
                            <img src="{{ asset($driver->image) }}" alt="Driver Image" class="card-img-top">
                            <div class="card-body">
                                <table class="table table-borderless mb-0">
                                    <tr>
                                        <th class="text-start" style="width: 40%;">Name</th>
                                        <td style="width: 5%;">:</td>
                                        <td><strong>{{ $driver->name }}</strong></td>
                                    </tr>
                                    <tr>
                                        <th class="text-start">Driver ID</th>
                                        <td>:</td>
                                        <td>{{ $driver->id }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-start">Kota</th>
                                        <td>:</td>
                                        <td>{{ $driver->city }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>        
    </form>
    
    <div class="container">
        {{ $drivers->onEachSide(5)->links('pagination::bootstrap-5') }}
    </div>

@endsection