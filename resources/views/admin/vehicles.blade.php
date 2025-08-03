@extends('admin.master')

@section('content')

<div class="container-fluid justify-content-between align-items-center mb-4">
    <form action="{{ route('admin.vehicles') }}" method="GET">
        <input name="search" class="form-control border-dark w-50 mx-auto my-2" placeholder="{{ __('admin_search_hints.vehicles') }}" aria-label="Search" value="{{ request('search') }}">
        
    </form>
</div>  

<div class="text-center">
    {{-- Add Vehicle Button --}}
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addVehicleModal">
        {{  __('admin_vehicles.add_vehicle') }}
    </button>
    <!-- Import Vehicle from CSV Button -->
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#importVehicleModal">
        {{ __('admin_vehicles.import_vehicles') }}
    </button>

</div>  


{{-- Add Vehicle Modal --}}
<div class="modal fade" id="addVehicleModal" tabindex="-1" aria-labelledby="addVehicleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="addVehicleModalLabel">{{  __('admin_vehicles.add_vehicle') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('admin.vehicles.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <dl class="row mb-0">
                        {{-- Vehicle Name --}}
                        <dt class="col-sm-3">{{ __('admin_tables.name') }}</dt>
                        <dd class="col-sm-9">
                            <select name="vehicle_name_id" class="form-select" required>
                                <option value="" disabled selected>{{  __('admin_vehicles.choose_vehicle_name') }}</option>
                                @foreach($vehicleNames as $name)
                                    <option value="{{ $name->id }}">{{ $name->name }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">{{ __('admin_vehicles.hint_vehicle_name') }}</small>
                        </dd>

                        {{-- Type --}}
                        <dt class="col-sm-3">{{ __('admin_tables.type') }}</dt>
                        <dd class="col-sm-9">
                            <select name="vehicle_type_id" class="form-select" required>
                                <option value="" disabled selected>{{  __('admin_vehicles.choose_vehicle_type') }}</option>
                                @foreach($vehicleTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->type }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">{{  __('admin_vehicles.hint_vehicle_type') }}</small>
                        </dd>

                        {{-- Transmission --}}
                        <dt class="col-sm-3">{{ __('admin_tables.transmission') }}</dt>
                        <dd class="col-sm-9">
                            <select name="vehicle_transmission_id" class="form-select" required>
                                <option value="" disabled selected>{{ __('admin_vehicles.choose_vehicle_transmission') }}</option>
                                @foreach($vehicleTransmissions as $trans)
                                    <option value="{{ $trans->id }}">{{ $trans->transmission }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">{{ __('admin_vehicles.hint_vehicle_transmission') }}</small>
                        </dd>

                        {{-- Engine CC --}}
                        <dt class="col-sm-3">{{ __('admin_tables.engine_cc') }}</dt>
                        <dd class="col-sm-9">
                            <input type="number" name="engine_cc" class="form-control" placeholder="{{ __('admin_vehicles.placeholder_engine_cc') }}" value="{{ old('engine_cc') }}" required>
                            <small class="text-muted">{{ __('admin_vehicles.hint_engine_cc') }}</small>
                        </dd>
                        
                        {{-- Seats --}}
                        <dt class="col-sm-3">{{ __('admin_tables.seats') }}</dt>
                        <dd class="col-sm-9">
                            <input type="number" name="seats" class="form-control" placeholder="{{ __('admin_vehicles.placeholder_seats') }}" value="{{ old('seats') }}" required>
                            <small class="text-muted">{{ __('admin_vehicles.hint_seats') }}</small>
                        </dd>

                        {{-- Year --}}
                        <dt class="col-sm-3">{{ __('admin_tables.year') }}</dt>
                        <dd class="col-sm-9">
                            <input type="number" name="year" class="form-control" placeholder="{{ __('admin_vehicles.placeholder_year') }}" value="{{ old('year') }}" required>
                            <small class="text-muted">{{ __('admin_vehicles.hint_year') }}</small>
                        </dd>

                        {{-- Price --}}
                        <dt class="col-sm-3">{{ __('admin_tables.price') }}</dt>
                        <dd class="col-sm-9">
                            <input type="number" name="price" class="form-control" placeholder="{{  __('admin_vehicles.placeholder_price') }}" value="{{ old('price') }}" required>
                            <small class="text-muted">{{  __('admin_vehicles.hint_price') }}</small>
                        </dd>

                        {{-- Location --}}
                        <dt class="col-sm-3">{{ __('admin_tables.location') }}</dt>
                        <dd class="col-sm-9">
                            <select name="location_id" class="form-select" required>
                                <option value="" disabled selected>{{ __('admin_vehicles.choose_location') }}</option>
                                @foreach($locations as $loc)
                                    <option value="{{ $loc->id }}">{{ $loc->location }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">{{ __('admin_vehicles.hint_location') }}</small>
                        </dd>

                        {{-- Main Image --}}
                        <dt class="col-sm-3">{{  __('admin_tables.main_image') }}</dt>
                        <dd class="col-sm-9">
                            <input class="form-control" type="file" name="main_image" accept=".jpg,.jpeg,.png" required>
                            <small class="text-muted">{{  __('admin_vehicles.hint_main_image') }}</small>
                        </dd>

                        {{-- Vehicle Images --}}
                        <dt class="col-sm-3">{{  __('admin_tables.vehicle_images') }}</dt>
                        <dd class="col-sm-9">
                            @for($i = 1; $i <= 4; $i++)
                                <div class="mb-2">
                                    <div class="text">{{ __('admin_vehicles.image') }} {{ $i }}</div>
                                    <input class="form-control" type="file" name="image{{ $i }}" accept=".jpg,.jpeg,.png">
                                    <small class="text-muted">{{  __('admin_vehicles.additional_image') }} {{ $i }} {{ __('admin_vehicles.hint_additional_image') }}</small>
                                </div>
                            @endfor
                        </dd>
                    </dl>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Vehicle</button>
                </div>
            </form>

        </div>
    </div>
</div>

<!-- Import Vehicles Modal -->
<div class="modal fade" id="importVehicleModal" tabindex="-1" aria-labelledby="importVehicleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('admin.vehicles.import') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="importVehicleModalLabel">{{ __('admin_vehicles.import_vehicles') }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
            <div class="mb-3">
                <label for="csv_file" class="form-label">CSV File</label>
                <input type="file" class="form-control" id="csv_file" name="csv_file" required>
                <div class="form-text mt-2">
                    {{ __('admin_vehicles.expected_format') }}<br>
                    <code>vehicle_type_id,vehicle_name_id,vehicle_transmission_id,engine_cc,seats,year,location_id,main_image_path,price,vehicle_image_1,vehicle_image_2,vehicle_image_3,vehicle_image_4,vehicle_categories</code><br>
                    - <strong>vehicle_categories</strong> {{ __('admin_vehicles.vehicle_categories_expected_format') }} <code>1,2,3</code>)<br>
                    - <strong>main_image_path</strong> {{ __('admin_vehicles.and') }} <strong>vehicle_image_X</strong> {{ __('admin_vehicles.images_path_expected_format') }}
                </div>
            </div>
        </div>



        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Import</button>
        </div>
      </div>
    </form>
  </div>
</div>

<div class="container-fluid mt-4">
    <div class="table-responsive" style="overflow-x: auto; overflow-y: hidden;">
        <table class="table table-bordered table-hover align-middle text-center w-100 table-striped">
            <thead class="table-light">
                <tr>
                    <th class="text-nowrap">ID</th>
                    <th class="text-nowrap">{{  __('admin_tables.main_image') }}</th>
                    <th class="text-nowrap">{{  __('admin_tables.name') }}</th>
                    <th class="text-nowrap">{{  __('admin_tables.type') }}</th>
                    <th class="text-nowrap">{{  __('admin_tables.transmission') }}</th>
                    <th class="text-nowrap">{{  __('admin_tables.engine_cc') }}</th>
                    <th class="text-nowrap">{{  __('admin_tables.seats') }}</th>
                    <th class="text-nowrap">{{  __('admin_tables.year') }}</th>
                    <th class="text-nowrap">{{  __('admin_tables.price') }}</th>
                    <th class="text-nowrap">{{  __('admin_tables.location') }}</th>
                    <th class="text-nowrap">{{  __('admin_tables.categories') }}</th>
                    <th class="text-nowrap">{{  __('admin_tables.rating') }}</th>
                    <th class="text-nowrap">{{  __('admin_tables.transactions') }}</th>
                    <th class="text-nowrap">{{  __('admin_vehicles.actions') }}</th>
                </tr>
            </thead>
            <tbody>
            @foreach($vehicles as $v)
                @php
                    $vehicleCategories = $v->vehicleCategories->pluck('category')->implode(', ');
                    $reviewsCount = $v->vehicleReview->count();
                    $transactionsCount = $v->transactions->count();
                    $badgeClass = $reviewsCount > 10 ? 'text-bg-success' : 'text-bg-secondary';
                    $images = $v->vehicleImages->take(4); 
                    $image1 = $images->get(0) ?? null;
                    $image2 = $images->get(1) ?? null;
                    $image3 = $images->get(2) ?? null;
                    $image4 = $images->get(3) ?? null;
                @endphp

                <tr data-bs-toggle="modal">
                    <td>{{ $v->id }}</td>
                    <td><img src="{{ asset($v->main_image) }}" alt="Vehicle Main Image" style="height: 8vh; width: 14vw;"></td>
                    <td><strong>{{ $v->vehicleName->name ?? 'N/A' }}</strong></td>
                    <td>{{ $v->vehicleType->type ?? 'N/A' }}</td>
                    <td>{{ $v->vehicleTransmission->transmission ?? 'N/A' }}</td>
                    <td>{{ $v->engine_cc ?? 'N/A'}}</td>
                    <td>{{ $v->seats ?? 'N/A' }}</td>
                    <td>{{ $v->year ?? 'N/A' }}</td>
                    <td>{{ $v->price ?? 'N/A' }}</td>
                    <td>{{ $v->location->location ?? 'N/A' }}</td>
                    <td>{{ $vehicleCategories ?: 'None' }}</td>
                    <td>{{ number_format($v->vehicleReview->avg('rate'), 1) ?? number_format(0, 1) }} ({{ $v->vehicleReview->count() }})</td>
                    <td>{{ $transactionsCount }}</td>
                    <td class="text-center">
                        <div class="d-flex flex-column gap-1">
                            <button class="btn btn-primary btn-sm text-nowrap" data-bs-toggle="modal" data-bs-target="#editVehicleModal{{ $v->id }}">
                                {{ __('admin_vehicles.edit_vehicle') }}
                            </button>
                            <button class="btn btn-warning btn-sm text-nowrap" data-bs-toggle="modal" data-bs-target="#editCategoryModal{{ $v->id }}">
                                {{ __('admin_vehicles.edit_categories') }}
                            </button>
                            <a href="{{ route('admin.vehicles.reviews', $v->id) }}" class="btn btn-info btn-sm text-nowrap">
                                {{ __('admin_vehicles.view_reviews') }}
                            </a>
                            <a href="{{ route('admin.transactions') }}?search={{ rawurlencode('vehicle_id=' . $v->id) }}" class="btn btn-secondary btn-sm text-nowrap">
                                {{ __('admin_vehicles.view_transactions') }}
                            </a>
                            <form action="{{ route('admin.vehicles.destroy', $v->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm text-nowrap"  >
                                    {{ __('admin_vehicles.destroy') }}
                                </button>
                            </form>

                        </div>
                    </td>
                </tr>
                {{-- Edit Vehicle Modal --}}
                <div class="modal fade" id="editVehicleModal{{ $v->id }}" tabindex="-1" aria-labelledby="editVehicleModalLabel{{ $v->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editVehicleModalLabel{{ $v->id }}">{{ __('admin_vehicles.vehicle_details') }} - #{{ $v->id }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            {{-- Form for updating vehicle --}}
                            <form action="{{ route('admin.vehicles.update', $v->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-body">
                                    <dl class="row mb-0">
                                        <dt class="col-sm-3">{{ __('admin_tables.name') }}</dt>
                                        <dd class="col-sm-9">
                                            <select name="vehicle_name_id" class="form-select" style="max-height: 50vh; overflow-y: auto;">
                                                @foreach($vehicleNames as $name)
                                                    <option value="{{ $name->id }}" @selected($v->vehicle_name_id == $name->id)>
                                                        {{ $name->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </dd>

                                        <dt class="col-sm-3">{{  __('admin_tables.type') }}</dt>
                                        <dd class="col-sm-9">
                                            <select name="vehicle_type_id" class="form-select" style="max-height: 50vh; overflow-y: auto;">
                                                @foreach($vehicleTypes as $type)
                                                    <option value="{{ $type->id }}" @selected($v->vehicle_type_id == $type->id)>
                                                        {{ $type->type }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </dd>

                                        <dt class="col-sm-3">{{  __('admin_tables.transmission') }}</dt>
                                        <dd class="col-sm-9">
                                            <select name="vehicle_transmission_id" class="form-select" style="max-height: 50vh; overflow-y: auto;">
                                                @foreach($vehicleTransmissions as $trans)
                                                    <option value="{{ $trans->id }}" @selected($v->vehicle_transmission_id == $trans->id)>
                                                        {{ $trans->transmission }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </dd>

                                        <dt class="col-sm-3">{{  __('admin_tables.engine_cc') }}</dt>
                                        <dd class="col-sm-9">
                                            <input type="number" name="engine_cc" class="form-control" value="{{ old('engine_cc', $v->engine_cc ?? 0) }}">
                                        </dd>
                                        
                                        <dt class="col-sm-3">{{  __('admin_tables.seats') }}</dt>
                                        <dd class="col-sm-9">
                                            <input type="number" name="seats" class="form-control" value="{{ old('seats', $v->seats ?? 0) }}">
                                        </dd>

                                        <dt class="col-sm-3">{{  __('admin_tables.year') }}</dt>
                                        <dd class="col-sm-9">
                                            <input type="number" name="year" class="form-control" value="{{ old('year', $v->year ?? 0) }}">
                                        </dd>

                                        <dt class="col-sm-3">{{  __('admin_tables.price') }}</dt>
                                        <dd class="col-sm-9">
                                            <input type="number" name="price" class="form-control" value="{{ old('price', $v->price ?? 0) }}">
                                        </dd>

                                        <dt class="col-sm-3">{{  __('admin_tables.location') }}</dt>
                                        <dd class="col-sm-9">
                                            <select name="location_id" class="form-select" style="max-height: 50vh; overflow-y: auto;">
                                                @foreach($locations as $loc)
                                                    <option value="{{ $loc->id }}" @selected($v->location_id == $loc->id)>
                                                        {{ $loc->location }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </dd>

                                        <dt class="col-sm-3">{{  __('admin_tables.categories') }}</dt>
                                        <dd class="col-sm-9">
                                            {{ $vehicleCategories }}
                                        </dd>
                                        
                                        <dt class="col-sm-3">{{  __('admin_tables.main_image') }}</dt>
                                        <dd class="col-sm-9">
                                            <img src="{{ asset($v->main_image) }}" 
                                                alt="Vehicle Main Image" 
                                                class="img-thumbnail mb-2" 
                                                style="max-width: 150px; border-radius: 8px;">

                                            <input class="form-control" type="file" id="main_image" name="main_image" accept=".jpg,.jpeg,.png">
                                            
                                            <small class="text-muted">{{  __('admin_vehicles.hint_image') }}</small>
                                        </dd>

                                        <dt class="col-sm-3">{{  __('admin_tables.vehicle_images') }}</dt>
                                        <dd class="col-sm-9">
                                            <div class="text">{{  __('admin_vehicles.image') }} 1</div>
                                            @if ($image1 != null)
                                                <input type="hidden" name="image1_id" value="{{ $image1->id }}">
                                                <img src="{{ asset($image1->image) }}" 
                                                alt="Vehicle Image 1" 
                                                class="img-thumbnail mb-2" 
                                                style="max-width: 150px; border-radius: 8px;">
                                            @endif
                                            <input class="form-control" type="file" id="image1" name="image1" accept=".jpg,.jpeg,.png">
                                            <small class="text-muted">{{  __('admin_vehicles.hint_image') }}</small>
                                            <div class="text">{{  __('admin_vehicles.image') }} 2</div>
                                            @if ($image2 != null)
                                                <input type="hidden" name="image2_id" value="{{ $image2->id }}">
                                                <img src="{{ asset($image2->image) }}" 
                                                alt="Vehicle Image 2" 
                                                class="img-thumbnail mb-2" 
                                                style="max-width: 150px; border-radius: 8px;">
                                            @endif
                                            <input class="form-control" type="file" id="image2" name="image2" accept=".jpg,.jpeg,.png">
                                            <small class="text-muted">{{  __('admin_vehicles.hint_image') }}</small>
                                            <div class="text">{{  __('admin_vehicles.image') }} 3</div>
                                            @if ($image3 != null)
                                                <input type="hidden" name="image3_id" value="{{ $image3->id }}">
                                                <img src="{{ asset($image3->image) }}" 
                                                alt="Vehicle Image 3" 
                                                class="img-thumbnail mb-2" 
                                                style="max-width: 150px; border-radius: 8px;">
                                            @endif
                                            <input class="form-control" type="file" id="image3" name="image3" accept=".jpg,.jpeg,.png">
                                            <small class="text-muted">{{  __('admin_vehicles.hint_image') }}</small>
                                            <div class="text">{{  __('admin_vehicles.image') }} 4</div>
                                            @if ($image4 != null)
                                                <input type="hidden" name="image4_id" value="{{ $image4->id }}">
                                                <img src="{{ asset($image4->image) }}" 
                                                alt="Vehicle Image 4" 
                                                class="img-thumbnail mb-2" 
                                                style="max-width: 150px; border-radius: 8px;">
                                                
                                            @endif
                                            <input class="form-control" type="file" id="image4" name="image4" accept=".jpg,.jpeg,.png">
                                            <small class="text-muted">{{  __('admin_vehicles.hint_image') }}</small>
                                        </dd>
                                        
                                    </dl>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{  __('admin_vehicles.close') }}</button>
                                    <button type="submit" class="btn btn-primary">{{  __('admin_vehicles.apply') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Edit Categories Modal --}}
                <div class="modal fade" id="editCategoryModal{{ $v->id }}" tabindex="-1" aria-labelledby="editCategoryModalLabel{{ $v->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">

                            <div class="modal-header">
                                <h5 class="modal-title" id="editCategoryModalLabel{{ $v->id }}">{{  __('admin_tables.vehicle_categories') }} - #{{ $v->id }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <div class="modal-body">

                                <div class="mb-4">
                                    <label class="form-label fw-bold">{{  __('admin_vehicles.current_categories') }}</label>
                                    <ul class="list-group">
                                        @forelse($v->vehicleCategories as $cat)
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <span>{{ $cat->category }}</span>
                                                <form action="{{ route('admin.vehicles.deleteCategory', $v->id) }}" method="POST" class="ms-2">
                                                    @csrf
                                                    <input type="hidden" name="category_id" value="{{ $cat->id }}">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Remove this category">&times;</button>
                                                </form>
                                            </li>
                                        @empty
                                            <li class="list-group-item text-muted fst-italic">{{  __('admin_vehicles.no_categories') }}</li>
                                        @endforelse
                                    </ul>
                                </div>

                                <hr>

                                <div class="mb-0">
                                    <label for="newCategorySelect-{{ $v->id }}" class="form-label fw-bold">{{  __('admin_vehicles.add_category') }}</label>
                                    <form action="{{ route('admin.vehicles.updateCategory', $v->id) }}" method="POST" class="row g-2 align-items-center">
                                        @csrf
                                        <div class="col-9">
                                            <select id="newCategorySelect-{{ $v->id }}" name="category_id" class="form-select" required>
                                                <option value="" selected disabled>{{  __('admin_vehicles.hint_choose') }}</option>
                                                @foreach($categories as $c)
                                                    @if(!$v->vehicleCategories->contains('id', $c->id))
                                                        <option value="{{ $c->id }}">{{ $c->category }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-3 d-grid">
                                            <button type="submit" class="btn btn-outline-primary">{{  __('admin_vehicles.add') }}</button>
                                        </div>
                                    </form>
                                </div>

                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{  __('admin_vehicles.close') }}</button>
                            </div>

                        </div>
                    </div>
                </div>
            @endforeach
            </tbody>
        </table>
    </div>
</div>



<div class="container d-flex justify-content-center my-4">
    {{ $vehicles->onEachSide(5)->links('pagination::bootstrap-5') }}
</div>
    
@endsection