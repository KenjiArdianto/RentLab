<?php

namespace App\Http\Controllers;

use App\Models\VehicleTransmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminVehicleTransmissionController extends Controller
{
    public function index(Request $request)
    {
        //
        $vehicleTransmissions = VehicleTransmission::query();


        // handle search
        $search = request('search');
        if ($search) {
            $vehicleTransmissions->where('transmission', 'like', '%' . $search . '%');
        }

        $vehicleTransmissions = $vehicleTransmissions->paginate(100)->appends(['search' => $search]);
         \activity('admin_vehicle_transmission_index')
        ->causedBy(Auth::user())
        ->withProperties([
            'search' => $search,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ])
        ->log("Admin searched vehicle transmissions with search = '{$search}'");

        return view('admin.vehicle-transmissions', compact('vehicleTransmissions'));

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
    public function store(Request $request)
    {
        //

        if (VehicleTransmission::where('transmission', $request->transmission)->exists()) {
            \activity('admin_vehicle_transmission_store_failed')
            ->causedBy(Auth::user())
            ->withProperties([
                'attempted_transmission' => $request->transmission,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ])
            ->log("Admin tried to create duplicate transmission '{$request->transmission}'");
            return back()->with('error', 'Vehicle Transmission Already Exists');
        }

        $transmission=VehicleTransmission::create([
            'transmission' => $request->transmission
        ]);
         \activity('admin_vehicle_transmission_store')
        ->causedBy(Auth::user())
        ->performedOn($transmission)
        ->withProperties([
            'transmission' => $request->transmission,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ])
        ->log("Admin created vehicle transmission '{$request->transmission}'");
        
        return back()->with('success', 'Vehicle Transmission Added Successfully');
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
    public function update(Request $request, VehicleTransmission $vehicleTransmission)
    {
        //
        // dd($vehicleTransmission);

        if ($vehicleTransmission->transmission === $request->transmission) {
            \activity('admin_vehicle_transmission_update_failed')
            ->causedBy(Auth::user())
            ->performedOn($vehicleTransmission)
            ->withProperties([
                'attempted_same_transmission' => $request->transmission,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ])
            ->log("Admin attempted to update vehicle transmission '{$request->transmission}' with no change");
            return back()->with('error', 'Vehicle Transmission Not Updated');
        }

        $old=$vehicleTransmission->transmission;
        $vehicleTransmission->transmission = $request->transmission;
        $vehicleTransmission->save();
        \activity('admin_vehicle_transmission_update')
        ->causedBy(Auth::user())
        ->performedOn($vehicleTransmission)
        ->withProperties([
            'old_transmission' => $old,
            'new_transmission' => $request->transmission,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ])
        ->log("Admin updated transmission from '{$old}' to '{$request->transmission}'");

        return back()->with('success', 'Vehicle Transmission Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VehicleTransmission $vehicleTransmission)
    {
        //
         \activity('admin_vehicle_transmission_delete')
        ->causedBy(Auth::user())
        ->performedOn($vehicleTransmission)
        ->withProperties([
            'deleted_transmission' => $vehicleTransmission->transmission,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ])
        ->log("Admin deleted vehicle transmission '{$vehicleTransmission->transmission}'");
        $vehicleTransmission->delete();

        return back()->with('success', 'Vehicle Transmission Deleted Successfully');
    }
}
