@extends('admin.master')

@section('content')

    <div class="container-fluid justify-content-between align-items-center mb-4">
        <form action="{{ route('admin.drivers') }}" method="GET">
            <input name="search" class="form-control border-dark w-50 mx-auto my-2" placeholder="Format: Attribute1=Value1,Attribute2=Value2 ex: driver_id=1,name=John" aria-label="Search">
            
        </form>
    </div>  

    <form action="{{ route('admin.drivers.delete') }}" method="POST">
        @csrf
        @method('DELETE')

        {{-- Button for delete multiple --}}
        <div class="d-flex justify-content-center">
            <button type="submit" class="btn btn-danger" name="action_type" value="deleteselected_0">Delete Selected</button>
        </div>

        {{-- Display Grid | 8 Row | 4 Col --}}
        <div class="container-flex m-4">
            <div class="row row-cols-1 row-cols-lg-4 g-4">

                {{-- Add New Driver Card --}}
                <div class="col">
                    <div class="mb-2 d-flex justify-content-center">
                        <input class="form-check-input" type="checkbox" name="selected[]" onclick="event.stopPropagation()" style="border: 1px solid black; box-shadow: 0 0 3px rgba(0,0,0,0.3); transform: scale(1.2); visibility: hidden;">
                    </div>

                    <div class="d-flex flex-column align-items-center">
                        <div class="card text-center d-flex align-items-center justify-content-center w-100" style="height: 100%; min-height: 350px; cursor: pointer; border: 2px dashed black;" data-bs-toggle="modal" data-bs-target="#addModal">
                            <span class="display-3">+</span>
                        </div>
                    </div>
                </div>
                 {{-- Driver Cards --}}
                @foreach ($drivers as $driver)
                    <div class="col">
                        <div class="d-flex flex-column align-items-center">
                            {{-- Centered Checkbox Above Card --}}
                            <div class="mb-2 d-flex justify-content-center">
                                <input class="form-check-input" type="checkbox" name="selected[]" value="{{ $driver->id }}" onclick="event.stopPropagation()" style="border: 1px solid black; box-shadow: 0 0 3px rgba(0,0,0,0.3); transform: scale(1.2);">
                            </div>

                            {{-- Card with Modal --}}
                            <div class="card w-100 h-100" style="min-height: 350px; cursor: pointer; border: 1px solid black;" data-bs-toggle="modal" data-bs-target="#editModal{{ $driver->id }}">
                                <img src="{{ asset($driver->image) }}" alt="Driver Image" class="card-img-top img-fluid" style="object-fit: cover; height: 200px;">
                                <div class="card-body p-3">
                                    <table class="table table-sm table-borderless mb-0">
                                        <tr>
                                            <th class="text-start">Driver ID</th>
                                            <td>:</td>
                                            <td>{{ $driver->id }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-start" style="width: 40%;">Name</th>
                                            <td style="width: 5%;">:</td>
                                            <td><strong>{{ $driver->name }}</strong></td>
                                        </tr>
                                        <tr>
                                            <th class="text-start">Location</th>
                                            <td>:</td>
                                            <td>{{ $driver->location->location }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>        
    </form>

    <script src="{{ asset('js/modal.js') }}"></script>

    <form action="{{ route('admin.drivers.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        {{-- Modal to Add Driver --}}
        <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="driverModalLabelAdd" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addModal">Add Driver</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-borderless mb-0">
                            <tr>
                                <input class="form-control" type="file" id="image" name="image" accept="image/*" required>
                            </tr>
                            <tr>
                                <th class="text-start" style="width: 40%;">Name</th>
                                <td style="width: 5%;">:</td>
                                <td>
                                    <input name="name" value="" type="text" class="form-control" placeholder="Insert Name" required>

                                </td>
                            </tr>
                            <tr>
                                <th class="text-start">Location</th>
                                <td>:</td>
                                <td>
                                    <select class="form-select selectpicker" name="location_id">
                                    <option value="" disabled selected hidden >Insert Location</option>
                                        @foreach ($locations as $location)
                                            <option value="{{ $location->id }}">{{ $location->location }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                        </table>

                        {{-- Buttons for apply changes and delete item --}}
                        <div class="container text-center">
                            <div class="row">
                                <div class="col">           
                                    <div class="mt-4 d-flex justify-content-center">
                                        <button type="submit" class="btn btn-danger" name="action_type" value="edit_{{ $driver->id }}">Apply</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </form>

    @foreach ($drivers as $driver)
        <form action="{{ route('admin.drivers.edit') }}" method="POST" enctype="multipart/form-data">
            @csrf
            {{-- Modal to Edit Driver --}}
            <div class="modal fade" id="editModal{{ $driver->id }}" tabindex="-1" aria-labelledby="driverModalLabel{{ $driver->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="driverModalLabel{{ $driver->id }}">Edit Driver</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="container-flex text-center mb-4">
                                <img src="{{ asset($driver->image) }}" alt="Driver Image">
                            </div>
                            <table class="table table-borderless mb-0">
                                <tr>
                                    <input class="form-control" type="file" id="image" name="image" accept=".jpg,.jpeg,.png">
                                </tr>
                                <tr>
                                    <th class="text-start">Driver ID</th>
                                    <td>:</td>
                                    <td>{{ $driver->id }}</td>
                                </tr>
                                <tr>
                                    <th class="text-start" style="width: 40%;">Name</th>
                                    <td style="width: 5%;">:</td>
                                    <td>
                                        <input name="name" value="{{ $driver->name }}" type="text" class="form-control">
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-start">Location</th>
                                    <td>:</td>
                                    <td>
                                        <select class="form-select" name="location_id">
                                        <option value="{{ $driver->location->id }}" selected>{{  $driver->location->location }}</option>
                                            @foreach ($locations as $location)
                                                <option value="{{ $location->id }}">{{ $location->location }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                            </table>

                            {{-- Buttons for apply changes and delete item --}}
                            <div class="container text-center">
                                <div class="row">
                                    <div class="col">           
                                        <div class="mt-4 d-flex justify-content-center">
                                            <button type="submit" class="btn btn-danger" name="action_type" value="edit_{{ $driver->id }}">Apply</button>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="mt-4 d-flex justify-content-center">
                                            <button type="submit" class="btn btn-danger" name="action_type" value="delete_{{ $driver->id }}">Delete</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </form>
    @endforeach
    
    <div class="container">
        {{ $drivers->onEachSide(5)->links('pagination::bootstrap-5') }}
    </div>

    <x-admin.feedback-modal/>
@endsection