<?php

namespace App\Http\Controllers;

use App\Models\VehicleType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminVehicleTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $vehicleTypes = VehicleType::query();


        // handle search
        $search = request('search');
        if ($search) {
            $vehicleTypes->where('type', 'like', '%' . $search . '%');
        }

        $vehicleTypes = $vehicleTypes->paginate(100)->appends(['search' => $search]);
        \activity('admin_vehicle_type_index')
        ->causedBy(Auth::user())
        ->withProperties([
            'search' => $search,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ])
        ->log("Admin searched vehicle types with search = '{$search}'");

        return view('admin.vehicle-types', compact('vehicleTypes'));

    }

    public function store(Request $request)
    {
        //

        if (VehicleType::where('type', $request->type)->exists()) {
            \activity('admin_vehicle_type_store_failed')
            ->causedBy(Auth::user())
            ->withProperties([
                'attempted_type' => $request->type,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ])
            ->log("Admin tried to create duplicate vehicle type '{$request->type}'");
            return back()->with('error', 'Vehicle Type Already Exists');
        }

        $vehicleType=VehicleType::create([
            'type' => $request->type
        ]);
        \activity('admin_vehicle_type_store')
            ->causedBy(Auth::user())
            ->performedOn($vehicleType)
            ->withProperties([
                'type' => $request->type,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ])
            ->log("Admin created vehicle type '{$request->type}'");
        
        return back()->with('success', 'Vehicle Type Added Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(VehicleType $vehicleType, Request $request)
    {
        //
        if ($vehicleType->type == $request->type) {
            \activity('admin_vehicle_type_update_failed')
            ->causedBy(Auth::user())
            ->performedOn($vehicleType)
            ->withProperties([
                'attempted_same_type' => $request->type,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ])
            ->log("Admin attempted to update vehicle type '{$request->type}' with no change");
            return back()->with('error', 'Vehicle Type Not Updated');
        }

        $old=$vehicleType->type;
        $vehicleType->type = $request->type;
        $vehicleType->save();
        \activity('admin_vehicle_type_update')
        ->causedBy(Auth::user())
        ->performedOn($vehicleType)
        ->withProperties([
            'old_type' => $old,
            'new_type' => $request->type,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ])
        ->log("Admin updated vehicle type from '{$old}' to '{$request->type}'");

        return back()->with('success', 'Vehicle Type Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VehicleType $vehicleType)
    {
        //
        \activity('admin_vehicle_type_delete')
        ->causedBy(Auth::user())
        ->performedOn($vehicleType)
        ->withProperties([
            'deleted_type' => $vehicleType->type,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ])
        ->log("Admin deleted vehicle type '{$vehicleType->type}'");
        $vehicleType->delete();

        return back()->with('success', 'Vehicle Type Deleted Successfully');
    }
}
