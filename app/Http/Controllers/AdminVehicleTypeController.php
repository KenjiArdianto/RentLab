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


        $search = request('search');
        if ($search) {
            $vehicleTypes->where('type', 'like', '%' . $search . '%');
        }

        $vehicleTypes = $vehicleTypes->paginate(100);

        return view('admin.vehicle-types', compact('vehicleTypes'));

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

        if (VehicleType::where('type', $request->type)->exists()) {
            return back()->with('error', 'Vehicle Type Already Exists');
        }

        VehicleType::create([
            'type' => $request->type
        ]);
        
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
            return back()->with('error', 'Vehicle Type Not Updated');
        }

        $vehicleType->type = $request->type;
        $vehicleType->save();

        return back()->with('success', 'Vehicle Type Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VehicleType $vehicleType)
    {
        //
        $vehicleType->delete();

        return back()->with('success', 'Vehicle Type Deleted Successfully');
    }
}
