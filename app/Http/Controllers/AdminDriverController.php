<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Driver;
use App\Models\Location;
use Illuminate\Support\Facades\File;
use App\Http\Requests\AdminDriverEditSelectedRequest;
use App\Http\Requests\AdminDriverDeleteSelectedRequest;
use Illuminate\Support\Facades\Auth;


class AdminDriverController extends Controller
{
    // View Driver List
    public function index(Request $request)
    {
        $locations = Location::all();
        $search = $request->query('search');

        $query = Driver::query()->with('location');

        // split search by comma
        if ($search) {
            $pairs = explode(',', $search);

            foreach ($pairs as $pair) {
                if (!str_contains($pair, '=')) continue;

                [$key, $value] = array_map('trim', explode('=', $pair, 2));
                
                // handle driver_id
                if ($key === 'driver_id' || $key === 'id_pengemudi') {
                    $query->where('id', $value);
                }
                // handle name
                else if ($key === 'name' || $key === 'nama') {
                    $query->where('name', 'like', '%' . $value . '%');
                }
                // handle location
                else if ($key === 'location' || $key === 'lokasi') {
                    $query->whereHas('location', function ($q) use ($value) {
                        $q->where('location', 'like', '%' . $value . '%');
                    });
                }
            }
        }

        // logging
        $drivers = $query->appends(['search' => $search]);
        \activity('admin_driver_index')
        ->causedBy(Auth::user())
        ->withProperties([
            'ip' => $request->ip(),
            'filters' => $request->query('search'),
            'user_agent' => $request->userAgent(),
        ])
         ->log('Admin viewed driver list' . ($request->has('search') ? ' with filters' : ''));
        return view('admin.drivers', compact('drivers', 'locations'));
    }

    public function store(Request $request)
    {
        // Store Image
        // Storing image in public folder
        $image = $request->file('image');
        $image_name = 'driver_image_' .  time() . '_' . $image->getClientOriginalName();
        $image->move(public_path('assets'), $image_name);

        $image_path = 'assets/' . $image_name;

        // // Storing Data in Model
        $driver=Driver::create([
            'name' => $request->name,
            'image' => $image_path,
            'location_id' => $request->location_id
        ]);

        // logging
        \activity('admin_driver_created')
        ->causedBy(Auth::user())
        ->performedOn($driver)
        ->withProperties([
            'ip' => $request->ip(),
            'input' => $request->except('image'),
            'driver_id' => $driver->id,
            'user_agent' => $request->userAgent(),
        ])
        ->log('Admin created a new driver');   
        return back()->with('success', 'Driver added.');
    }

    public function editSelected(AdminDriverEditSelectedRequest $request)
    {
        // Format variable action_type dari request itu Operation_DriverID
        // Jadi disini di split dulu dengan _ sebagai separator
    
        // $request = $request->validate();

        list($action_type, $driver_id) = explode('_', $request->input('action_type'));

        if ($action_type == 'delete') {

            // get driver object
            $driver = Driver::where('id', $driver_id)->first();

            // delete driver image from folderas
            File::delete(public_path($driver->image));

            // logging
            \activity('admin_driver_deleted')
            ->causedBy(Auth::user())
            ->performedOn($driver)
            ->withProperties([
                'ip' => $request->ip(),
                'driver_id' => $driver_id,
                'driver_name' => $driver->name,
                'user_agent' => $request->userAgent(),
            ])
            ->log('Admin deleted a driver');

            // delete driver object itself
            $driver->delete();
            
            return back()->with('success', 'Selected driver deleted.');
        }
        else if ($action_type == 'edit') {

            // get driver object
            $driver = Driver::where('id', $driver_id)->first();
            
            // if the request have image in it then delete original image and store new image, also replace old path with new path
            if($request->hasFile('image')){ 
                $image = $request->file('image');
                $image_name = 'driver_image_' .  time() . '_' . $image->getClientOriginalName();
                File::delete(public_path($driver->image));
                $image->move(public_path('assets'), $image_name);

                $image_path = 'assets/' . $image_name;

                $driver->image = $image_path;

            }
            
            // if request have name then replace old name with new name
            if ($request->has('name')) {
                $driver->name = $request->input('name');
            }

            // if request have location then replace old location with new location
            if ($request->has('location_id')) {
                $driver->location_id = $request->input('location_id');
            }

            // logging
            $driver->save();
            \activity('admin_driver_edited')
            ->causedBy(Auth::user())
            ->performedOn($driver)
            ->withProperties([
                'ip' => $request->ip(),
                'driver_id' => $driver_id,
                'input' => $request->except('image'),
                'user_agent' => $request->userAgent(),
            ])
            ->log('Admin edited a driver');
            
            return redirect()->back()->with('success', 'Selected driver edited.');
        }
    }

    public function deleteSelected(AdminDriverDeleteSelectedRequest $request)
    {
        $id_list = $request->input('selected', []);

        // delete all selected driver ids
        Driver::whereIn('id', $id_list)->delete();

        // logging
        \activity('admin_driver_bulk_deleted')
        ->causedBy(Auth::user())
        ->withProperties([
            'ip' => $request->ip(),
            'driver_ids' => $id_list,
            'count_deleted' => count($id_list),
            'user_agent' => $request->userAgent(),
        ])
        ->log('Admin deleted multiple drivers');

        if (!$id_list) {
            return back()->with('error', 'No drivers selected.');
        }
        
        return back()->with('success', 'Selected drivers deleted.');
    }
}
