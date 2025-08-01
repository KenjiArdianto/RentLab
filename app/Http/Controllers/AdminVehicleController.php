<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Vehicle;
use App\Models\VehicleCategory;
use App\Models\VehicleImage;
use App\Models\VehicleType;
use App\Models\VehicleName;
use App\Models\VehicleTransmission;
use App\Models\VehicleReview;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use App\Http\Requests\AdminVehicleStoreRequest;
use Illuminate\Support\Facades\Auth;


class AdminVehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //

        $vehicleTypes = VehicleType::all();
        $vehicleNames = VehicleName::all();
        $vehicleTransmissions = VehicleTransmission::all();
        $locations = Location::all();
        $categories = VehicleCategory::all();
        // dd($categories->all());

        $search = $request->query('search');
        $query = Vehicle::query();

        // split search by comma
        if ($search) {
            $pairs = explode(',', $search);

            foreach ($pairs as $pair) {
                if (!str_contains($pair, '=')) continue;

                [$key, $value] = array_map('trim', explode('=', $pair, 2));
                
                // handle vehicle_id
                if ($key === 'vehicle_id') {
                    $query->where('id', $value);
                }
                // handle type
                else if ($key === 'type') {
                    $query->whereHas('vehicleType', function ($q) use ($value) {
                        $q->where('type', $value);
                    });
                }
                // handle transmission
                else if ($key === 'transmission') {
                    $query->whereHas('vehicleTransmission', function ($q) use ($value) {
                        $q->where('transmission', $value);
                    });
                }
                // handle engine_cc
                else if ($key === 'engine_cc') {
                    $query->where('engine_cc', $value);
                }
                // handle seats
                else if ($key === 'seats') {
                    $query->where('seats', $value);
                }
                // handle year
                else if ($key === 'year') {
                    $query->where('year', $value);
                }
                // handle price
                else if ($key === 'price') {
                    $query->where('price', $value);
                }
                // handle location
                else if ($key === 'location') {
                    $query->whereHas('location', function ($q) use ($value) {
                        $q->where('location', $value);
                    });
                }
                // handle rating
                else if ($key === 'rating') {
                    $query->whereHas('vehicleReview', function ($q) use ($value) {
                        $q->selectRaw('vehicle_id, AVG(rate) as avg_rating')
                        ->groupBy('vehicle_id')
                        ->havingRaw('FLOOR(avg_rating) = ?', [(int)$value]);
                    });
                }
                // handle transactions
                else if ($key === 'transactions') {
                    $query->whereHas('transactions', function ($q) {
                        $q->select('vehicle_id')
                        ->groupBy('vehicle_id');
                    })
                    ->withCount('transactions')
                    ->having('transactions_count', '=', (int)$value);
                }

            }
        }


        
        // dd($request->all());

    
        $vehicles = $query
            ->with(['vehicleName', 'vehicleType', 'vehicleTransmission', 'vehicleCategories', 'location', 'vehicleReview', 'transactions', 'vehicleImages'])
            ->paginate(100)->appends(['search' => $search]);

        activity('admin_vehicle_index')
        ->causedBy(Auth::user())
        ->withProperties([
            'ip' => $request->ip(),
            'search_query' => $search,
            'result_count' => $vehicles->total(),
            'user_agent' => $request->userAgent(),
        ])
        ->log('Admin viewed vehicle list' . ($request->has('search') ? ' with filters' : ''));


        return view('admin.vehicles', compact('vehicles', 'vehicleTypes', 'vehicleNames', 'vehicleTransmissions', 'locations', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AdminVehicleStoreRequest $request)
    {
        // Store Data in Variable Temporarily
        $data = [
            'vehicle_name_id'        => $request->vehicle_name_id,
            'vehicle_type_id'        => $request->vehicle_type_id,
            'vehicle_transmission_id'=> $request->vehicle_transmission_id,
            'engine_cc'              => $request->engine_cc,
            'seats'                  => $request->seats,
            'price'                  => $request->price,
            'location_id'            => $request->location_id,
            'main_image'             => null,  // Set default null
        ];

        // Save Main Image
        if ($request->hasFile('main_image') && $request->file('main_image')->isValid()) {
            $extension = $request->file('main_image')->getClientOriginalExtension();
            $fileName = 'vehicle_image_main_' . time() . '.' . $extension;
            $request->file('main_image')->move(public_path('assets'), $fileName);
            $mainImagePath = 'assets/' . $fileName;
            // Insert Main Image Path to Temporary Variable
            $data['main_image'] = $mainImagePath;
        }

        // Store Vehicle Data in Database
        $vehicle = Vehicle::create($data);

        // Save Image 1
        if ($request->hasFile('image1') && $request->file('image1')->isValid()) {
            $extension = $request->file('image1')->getClientOriginalExtension();
            $fileName = 'vehicle_image_1_' . time() . '.' . $extension;
            $request->file('image1')->move(public_path('assets'), $fileName);
            $image1Path = 'assets/' . $fileName;

            $vehicle->vehicleImages()->create(['image' => $image1Path]);
        }

        // Save Image 2
        if ($request->hasFile('image2') && $request->file('image2')->isValid()) {
            $extension = $request->file('image2')->getClientOriginalExtension();
            $fileName = 'vehicle_image_2_' . time() . '.' . $extension;
            $request->file('image2')->move(public_path('assets'), $fileName);
            $image2Path = 'assets/' . $fileName;
            
            $vehicle->vehicleImages()->create(['image' => $image2Path]);
        }

        // Save Image 3
        if ($request->hasFile('image3') && $request->file('image3')->isValid()) {
            $extension = $request->file('image3')->getClientOriginalExtension();
            $fileName = 'vehicle_image_3_' . time() . '.' . $extension;
            $request->file('image3')->move(public_path('assets'), $fileName);
            $image3Path = 'assets/' . $fileName;
            
            $vehicle->vehicleImages()->create(['image' => $image3Path]);
        }

        // Save Image 4
        if ($request->hasFile('image4') && $request->file('image4')->isValid()) {
            $extension = $request->file('image4')->getClientOriginalExtension();
            $fileName = 'vehicle_image_4_' . time() . '.' . $extension;
            $request->file('image4')->move(public_path('assets'), $fileName);
            $image4Path = 'assets/' . $fileName;
            
            $vehicle->vehicleImages()->create(['image' => $image4Path]);
        }

        activity('admin_vehicle_store')
        ->causedBy(Auth::user())
        ->performedOn($vehicle)
        ->withProperties([
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ])
        ->log("Admin created vehicle #$vehicle->id");

        return back()->with('success', 'Vehicle added.');

    }

    /**
     * Display the specified resource.
     */
    public function show(Vehicle $vehicle)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vehicle $vehicle)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vehicle $vehicle)
    {
        //
        // dd($request->all());

        
        $changes = [];
        $uploadErrors = [];

        $vehicleUpdated = false;

        if ($request->vehicle_name_id != $vehicle->vehicle_name_id) {
            $changes['vehicle_name_id'] = [
                'old' => $vehicle->vehicle_name_id,
                'new' => $request->vehicle_name_id,
            ];
            $vehicleUpdated = true;

            $vehicle->vehicle_name_id = $request->vehicle_name_id;
        }

        if ($request->vehicle_type_id != $vehicle->vehicle_type_id) {
            $changes['vehicle_type_id'] = [
                'old' => $vehicle->vehicle_type_id,
                'new' => $request->vehicle_type_id,
            ];
            $vehicleUpdated = true;

            $vehicle->vehicle_type_id = $request->vehicle_type_id;
        }

        if ($request->vehicle_transmission_id != $vehicle->vehicle_transmission_id) {
            $changes['vehicle_transmission_id'] = [
                'old' => $vehicle->vehicle_transmission_id,
                'new' => $request->vehicle_transmission_id,
            ];
            $vehicleUpdated = true;

            $vehicle->vehicle_transmission_id = $request->vehicle_transmission_id;
        }

        if ($request->engine_cc != $vehicle->engine_cc) {
            $changes['engine_cc'] = [
                'old' => $vehicle->engine_cc,
                'new' => $request->engine_cc,
            ];
            $vehicleUpdated = true;

            $vehicle->engine_cc = $request->engine_cc;
        }

        if ($request->seats != $vehicle->seats) {
            $changes['seats'] = [
                'old' => $vehicle->seats,
                'new' => $request->seats,
            ];
            $vehicleUpdated = true;

            $vehicle->seats = $request->seats;
        }

        if ($request->price != $vehicle->price) {
            $changes['price'] = [
                'old' => $vehicle->price,
                'new' => $request->price,
            ];
            $vehicleUpdated = true;

            $vehicle->price = $request->price;
        }

        if ($request->location_id != $vehicle->location_id) {
            $changes['location'] = [
                'old' => $vehicle->location_id,
                'new' => $request->location_id,
            ];
            $vehicleUpdated = true;

            $vehicle->location_id = $request->location_id;
        }

        // dd($request->all());

        if ($request->hasFile('main_image') && $request->file('main_image')->isValid()) {
            try {
                $vehicleUpdated = true;

                if ($vehicle->main_image && File::exists(public_path($vehicle->main_image))) {
                    File::delete(public_path($vehicle->main_image));
                }

                $extension = $request->file('main_image')->getClientOriginalExtension();
                $fileName = 'vehicle_image_main_' . time() . '.' . $extension;
                $request->file('main_image')->move(public_path('assets'), $fileName);
                $mainImagePath = 'assets/' . $fileName;

                $vehicle->main_image = $mainImagePath;
            } catch (\Exception $e) {
                $uploadErrors[] = 'main_image failed: ' . $e->getMessage();
            }
        }

        if ($request->hasFile('image1') && $request->file('image1')->isValid()) {
            try {
                $vehicleUpdated = true;

                $extension = $request->file('image1')->getClientOriginalExtension();
                $fileName = 'vehicle_image_1_' . time() . '.' . $extension;
                $request->file('image1')->move(public_path('assets'), $fileName);
                $newPath = 'assets/' . $fileName;

                if ($request->image1_id) {
                    $img = VehicleImage::find($request->image1_id);
                    if ($img) {
                        if ($img->image && File::exists(public_path($img->image))) {
                            File::delete(public_path($img->image));
                        }
                        $img->update(['image' => $newPath]);
                    } else {
                        $vehicle->vehicleImages()->create(['image' => $newPath]);
                    }
                } else {
                    $vehicle->vehicleImages()->create(['image' => $newPath]);
                }
            } catch (\Exception $e) {
                $uploadErrors[] = 'Image1 failed: ' . $e->getMessage();
            }
        }

        if ($request->hasFile('image2') && $request->file('image2')->isValid()) {
            try{
                $vehicleUpdated = true;

                $extension = $request->file('image2')->getClientOriginalExtension();
                $fileName = 'vehicle_image_2_' . time() . '.' . $extension;
                $request->file('image2')->move(public_path('assets'), $fileName);
                $newPath = 'assets/' . $fileName;

                if ($request->image2_id) {
                    $img = VehicleImage::find($request->image2_id);
                    if ($img) {
                        if ($img->image && File::exists(public_path($img->image))) {
                            File::delete(public_path($img->image));
                        }
                        $img->update(['image' => $newPath]);
                    } else {
                        $vehicle->vehicleImages()->create(['image' => $newPath]);
                    }
                } else {
                    $vehicle->vehicleImages()->create(['image' => $newPath]);
                }
            } catch (\Exception $e) {
                $uploadErrors[] = 'Image2 failed: ' . $e->getMessage();
            }
        }

        if ($request->hasFile('image3') && $request->file('image3')->isValid()) {
            try{
                $vehicleUpdated = true;

                $extension = $request->file('image3')->getClientOriginalExtension();
                $fileName = 'vehicle_image_3_' . time() . '.' . $extension;
                $request->file('image3')->move(public_path('assets'), $fileName);
                $newPath = 'assets/' . $fileName;


                if ($request->image3_id) {
                    $img = VehicleImage::find($request->image3_id);
                    if ($img) {
                        if ($img->image && File::exists(public_path($img->image))) {
                            File::delete(public_path($img->image));
                        }
                        $img->update(['image' => $newPath]);
                    } else {
                        $vehicle->vehicleImages()->create(['image' => $newPath]);
                    }
                } else {
                    $vehicle->vehicleImages()->create(['image' => $newPath]);
                }
            } catch (\Exception $e) {
                $uploadErrors[] = 'Image3 failed: ' . $e->getMessage();
            }
        }

        if ($request->hasFile('image4') && $request->file('image4')->isValid()) {
            try{
                $vehicleUpdated = true;

                $extension = $request->file('image4')->getClientOriginalExtension();
                $fileName = 'vehicle_image_4_' . time() . '.' . $extension;
                $request->file('image4')->move(public_path('assets'), $fileName);
                $newPath = 'assets/' . $fileName;

                if ($request->image4_id) {
                    $img = VehicleImage::find($request->image4_id);
                    if ($img) {
                        if ($img->image && File::exists(public_path($img->image))) {
                            File::delete(public_path($img->image));
                        }
                        $img->update(['image' => $newPath]);
                    } else {
                        $vehicle->vehicleImages()->create(['image' => $newPath]);
                    }
                } else {
                    $vehicle->vehicleImages()->create(['image' => $newPath]);
                }
            } catch (\Exception $e) {
                $uploadErrors[] = 'Image4 failed: ' . $e->getMessage();
            }
        }

        if ($vehicleUpdated) {
            \activity('admin_vehicle_update_successful')
            ->causedBy(Auth::user())
            ->performedOn($vehicle)
            ->withProperties([
                'ip' => $request->ip(),
                'changes' => $changes ?: null,
                'upload_errors' => $uploadErrors ?: null,
                'user_agent' => $request->userAgent(),
            ])
            ->log("Admin updated vehicle #$vehicle->id");
            return back()->with('success', "Vehicle #$vehicle->id updated.");

        }
        \activity('admin_vehicle_update_failed')
        ->causedBy(Auth::user())
        ->performedOn($vehicle)
        ->withProperties([
            'ip' => $request->ip(),
            'attempted_input' => $request->except(['_token']),
            'user_agent' => $request->userAgent(),
        ])
        ->log("Admin attempted to update vehicle #$vehicle->id, but no changes were made.");
        return back()->with('error', "Vehicle #$vehicle->id not updated.");
    }

    public function updateCategory(Request $request, Vehicle $vehicle)
    {
        //
        // dd($request->all());

        if (VehicleCategory::where('category', $request->category)->exists()) {
            // The category already exists
            \activity('admin_vehicle_category_add_failed')
            ->causedBy(Auth::user())
            ->performedOn($vehicle)
            ->withProperties([
                'ip' => $request->ip(),
                'category_attempted' => $request->category,
                'user_agent' => $request->userAgent(),
            ])
            ->log("Admin failed to add existing category '{$request->category}' to vehicle #$vehicle->id");
            return back()->with('error', 'Vehicle already has this category.');
        }

        $vehicle->vehicleCategories()->attach($request->category_id);
        \activity('admin_vehicle_category_add')
        ->causedBy(Auth::user())
        ->performedOn($vehicle)
        ->withProperties([
            'ip' => $request->ip(),
            'category_id' => $request->category_id,
            'user_agent' => $request->userAgent(),
        ])
        ->log("Admin added category ID {$request->category_id} to vehicle #$vehicle->id");
        return back()->with('success', "Vehicle #$vehicle->id category added.");
    }

    public function deleteCategory(Request $request, Vehicle $vehicle)
    {
        //
        // dd($request->all());

        $vehicle->vehicleCategories()->detach($request->category_id);
        \activity('admin_vehicle_category_remove')
        ->causedBy(Auth::user())
        ->performedOn($vehicle)
        ->withProperties([
            'ip' => $request->ip(),
            'category_id' => $request->category_id,
            'user_agent' => $request->userAgent(),
        ])
        ->log("Admin removed category ID {$request->category_id} from vehicle #$vehicle->id");
        return back()->with('success', "Vehicle #$vehicle->id category removed.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vehicle $vehicle)
    {
        //
    }
}
