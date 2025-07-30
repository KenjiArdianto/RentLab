<?php

namespace App\Http\Controllers;

use App\Models\VehicleTransmission;
use Illuminate\Http\Request;

class AdminVehicleTransmissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $vehicleTransmissions = VehicleTransmission::query();


        $search = request('search');
        if ($search) {
            $vehicleTransmissions->where('transmission', 'like', '%' . $search . '%');
        }

        $vehicleTransmissions = $vehicleTransmissions->paginate(100);

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
            return back()->with('error', 'Vehicle Transmission Already Exists');
        }

        VehicleTransmission::create([
            'transmission' => $request->transmission
        ]);
        
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
            return back()->with('error', 'Vehicle Transmission Not Updated');
        }

        $vehicleTransmission->transmission = $request->transmission;
        $vehicleTransmission->save();

        return back()->with('success', 'Vehicle Transmission Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VehicleTransmission $vehicleTransmission)
    {
        //
        $vehicleTransmission->delete();

        return back()->with('success', 'Vehicle Transmission Deleted Successfully');
    }
}
