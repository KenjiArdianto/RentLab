@extends('admin.master')

@section('content')

    <div class="container-fluid justify-content-between align-items-center mb-4">
        <form action="{{ route('admin.drivers.search') }}" method="GET">
            <input name="search" class="form-control border-dark w-50 mx-auto my-2" placeholder="Format: Attribute1=Value1,Attribute2=Value2 ex: driver_id=1,name=John" aria-label="Search">
            
        </form>
    </div>  

    <div class="container-flex m-4">
        <table class="table table-bordered table-hover align-middle text-center mx-auto" style="cursor: pointer;">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Main Image</th>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Transmission</th>
                    <th>Engine CC</th>
                    <th>Seats</th>
                    <th>Price</th>
                    <th>Location</th>
                    <th>Categories</th>
                    <th>Reviews</th>
                    <th>Transactions</th>
                </tr>
            </thead>
            <tbody>
            @foreach($vehicles as $v)
                @php
                    $vehicleCategories = $v->vehicleCategories->pluck('category')->implode(', ');
                    $reviewsCount = $v->vehicleReview->count();
                    $transactionsCount = $v->transactions->count();
                    $badgeClass = $reviewsCount > 10 ? 'text-bg-success' : 'text-bg-secondary';
                @endphp

                <tr data-bs-toggle="modal" data-bs-target="#editVehicleModal{{ $v->id }}">
                    <td>{{ $v->id }}</td>
                    <td><img src="{{ asset($v->main_image) }}" alt="Vehicle Main Image" style="height: 8h; width: 14vw;"></td>
                    <td><strong>{{ $v->vehicleName->name ?? 'N/A' }}</strong></td>
                    <td>{{ $v->vehicleType->type ?? 'N/A' }}</td>
                    <td>{{ $v->vehicleTransmission->transmission ?? 'N/A' }}</td>
                    <td>{{ $v->engine_cc ?? 'N/A'}}</td>
                    <td>{{ $v->seats ?? 'N/A' }}</td>
                    <td>{{ $v->price ?? 'N/A' }}</td>
                    <td>{{ $v->location->location ?? 'N/A' }}</td>
                    <td>{{ $vehicleCategories ?: 'None' }}</td>
                    <td>{{ number_format($v->vehicleReview->avg('rate'), 1) ?? number_format(0, 1) }} ({{ $v->vehicleReview->count() }})</td>
                    <td>{{ $transactionsCount }}</td>
                </tr>

                {{-- Modal --}}
                <div class="modal fade" id="editVehicleModal{{ $v->id }}" tabindex="-1" aria-labelledby="editVehicleModalLabel{{ $v->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editVehicleModalLabel{{ $v->id }}">Vehicle Detail - #{{ $v->id }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            {{-- Form for updating vehicle --}}
                            <form action="{{ route('admin.vehicles.update', $v->id) }}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <dl class="row mb-0">
                                        <dt class="col-sm-3">Name</dt>
                                        <dd class="col-sm-9">
                                            <select name="vehicle_name_id" class="form-select" style="max-height: 50vh; overflow-y: auto;">
                                                @foreach($vehicleNames as $name)
                                                    <option value="{{ $name->id }}" @selected($v->vehicle_name_id == $name->id)>
                                                        {{ $name->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </dd>

                                        <dt class="col-sm-3">Type</dt>
                                        <dd class="col-sm-9">
                                            <select name="vehicle_type_id" class="form-select" style="max-height: 50vh; overflow-y: auto;">
                                                @foreach($vehicleTypes as $type)
                                                    <option value="{{ $type->id }}" @selected($v->vehicle_type_id == $type->id)>
                                                        {{ $type->type }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </dd>

                                        <dt class="col-sm-3">Transmission</dt>
                                        <dd class="col-sm-9">
                                            <select name="vehicle_transmission_id" class="form-select" style="max-height: 50vh; overflow-y: auto;">
                                                @foreach($vehicleTransmissions as $trans)
                                                    <option value="{{ $trans->id }}" @selected($v->vehicle_transmission_id == $trans->id)>
                                                        {{ $trans->transmission }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </dd>

                                        <dt class="col-sm-3">Location</dt>
                                        <dd class="col-sm-9">
                                            <select name="location_id" class="form-select" style="max-height: 50vh; overflow-y: auto;">
                                                @foreach($locations as $loc)
                                                    <option value="{{ $loc->id }}" @selected($v->location_id == $loc->id)>
                                                        {{ $loc->location }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </dd>

                                        <dt class="col-sm-3">Price</dt>
                                        <dd class="col-sm-9">
                                            <input type="number" name="price" class="form-control" value="{{ old('price', $v->price ?? 0) }}">
                                        </dd>

                                        <dt class="col-sm-3">Categories</dt>
                                        <dd class="col-sm-9">
                                            <div id="categories-container-{{ $v->id }}">
                                                @foreach($v->vehicleCategories as $cat)
                                                    <div class="category-row mb-2 d-flex gap-2">
                                                        <select name="categories[]" class="form-select">
                                                            @foreach($categories as $c)
                                                                <option value="{{ $c->id }}" @selected($cat->id == $c->id)>
                                                                    {{ $c->category }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <button type="button" class="btn btn-danger btn-sm remove-category">x</button>
                                                    </div>
                                                @endforeach
                                                
                                            </div>
                                            <button type="button" class="btn btn-outline-primary btn-sm mt-2" 
                                                    onclick="addCategoryRow{{ $v->id }}()">+ Add Category</button>
                                            

                                        </dd>



                                        <dt class="col-sm-3">Description</dt>
                                        <dd class="col-sm-9">
                                            <textarea name="description" class="form-control" rows="3" maxlength="500" 
                                                oninput="updateCharCount(this, 'descCounter-{{ $v->id }}')"
                                                placeholder="Write description here...">{{ old('description', $v->description ?? '') }}</textarea>
                                            <small class="text-muted">
                                                <span id="descCounter-{{ $v->id }}">0</span>/500 characters
                                            </small>
                                        </dd>

                                        <dt class="col-sm-3">Main Image</dt>
                                        <dd class="col-sm-9">
                                            <img src="{{ asset($v->main_image) }}" 
                                                alt="Vehicle Image" 
                                                style="max-width: 150px; border-radius: 8px;">
                                        </dd>
                                    </dl>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Apply</button>
                                </div>

                                <script>
                                    // Function to add a new category dropdown
                                    function addCategoryRow{{ $v->id }}() {
                                        const container = document.getElementById('categories-container-{{ $v->id }}');

                                        // Create a new row for category dropdown
                                        const newRow = document.createElement('div');
                                        newRow.classList.add('category-row', 'mb-2', 'd-flex', 'gap-2');

                                        // Dropdown + remove button
                                        newRow.innerHTML = `
                                            <select name="categories[]" class="form-select">
                                                @foreach($categories as $c)
                                                    <option value="{{ $c->id }}">{{ $c->category }}</option>
                                                @endforeach
                                            </select>
                                            <button type="button" class="btn btn-danger btn-sm remove-category">x</button>
                                        `;

                                        // Append the new row to the container
                                        container.appendChild(newRow);
                                    }

                                    // Event delegation for remove buttons
                                    document.addEventListener('click', function (e) {
                                        if (e.target.classList.contains('remove-category')) {
                                            e.preventDefault();
                                            e.target.closest('.category-row').remove(); // Remove the parent row
                                        }
                                    });
                                </script>
                            </form>
                        </div>
                    </div>
                </div>

            @endforeach
            </tbody>
        </table>
    </div>

    


    <div class="container">
        {{ $vehicles->onEachSide(5)->links('pagination::bootstrap-5') }}
    </div>

    
@endsection