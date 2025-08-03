<?php

namespace App\Http\Controllers;

use App\Models\VehicleName;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminVehicleNameController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // view and search
        $vehicleNames = VehicleName::query();


        $search = request('search');
        if ($search) {
            $vehicleNames->where('name', 'like', '%' . $search . '%');
        }

        $vehicleNames = $vehicleNames->paginate(100)->appends(['search' => $search]);
        \activity('admin_vehicle_name_index')
        ->causedBy(Auth::user())
        ->withProperties([
            'search' => $search,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ])
        ->log("Admin searched vehicle names with search = '{$search}'");

        return view('admin.vehicle-names', compact('vehicleNames'));

    }

    public function store(Request $request)
    {
        //

        // check if already exist
        if (VehicleName::where('name', $request->name)->exists()) {
            \activity('admin_vehicle_name_store_failed')
            ->causedBy(Auth::user())
            ->withProperties([
                'ip' => $request->ip(),
                'attempted_name' => $request->name,
                'user_agent' => $request->userAgent(),
            ])
            ->log("Admin tried to create duplicate vehicle name '{$request->name}'");

            return back()->with('error', 'Vehicle Name Already Exists');
        }

        $vehicleName=VehicleName::create([
            'name' => $request->name
        ]);
        \activity('admin_vehicle_name_store')
        ->causedBy(Auth::user())
        ->performedOn($vehicleName)
        ->withProperties([
            'name' => $request->name,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ])
        ->log("Admin created vehicle name '{$request->name}'");
        
        return back()->with('success', 'Vehicle Name Added Successfully');
    }

    public function update(VehicleName $vehicleName, Request $request)
    {
        //
        // dd($vehicleName->name);
        // update vehicle name
        if ($vehicleName->name == $request->name) {
            \activity('admin_vehicle_name_update_failed')
            ->causedBy(Auth::user())
            ->performedOn($vehicleName)
            ->withProperties([
                'attempted_same_name' => $request->name,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ])
            ->log("Admin attempted to update vehicle name '{$request->name}' with no change");
            return back()->with('error', 'Vehicle Name Not Updated');
        }

        $old=$vehicleName->name;
        $vehicleName->name = $request->name;
        $vehicleName->save();
        \activity('admin_vehicle_name_update')
        ->causedBy(Auth::user())
        ->performedOn($vehicleName)
        ->withProperties([
            'old_name' => $old,
            'new_name' => $request->name,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ])
        ->log("Admin updated vehicle name from '{$old}' to '{$request->name}'");

        return back()->with('success', 'Vehicle Name Updated Successfully');
    }

    public function destroy(VehicleName $vehicleName)
    {
        //

        // remove vehicle name
        \activity('admin_vehicle_name_delete')
        ->causedBy(Auth::user())
        ->performedOn($vehicleName)
        ->withProperties([
            'deleted_name' => $vehicleName->name,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ])
        ->log("Admin deleted vehicle name '{$vehicleName->name}'");
        $vehicleName->delete();
        

        return back()->with('success', 'Vehicle Name Deleted Successfully');
    }
}
