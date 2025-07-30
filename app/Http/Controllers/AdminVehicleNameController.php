<?php

namespace App\Http\Controllers;

use App\Models\VehicleName;
use Illuminate\Http\Request;

class AdminVehicleNameController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $vehicleNames = VehicleName::query();


        $search = request('search');
        if ($search) {
            $vehicleNames->where('name', 'like', '%' . $search . '%');
        }

        $vehicleNames = $vehicleNames->paginate(100);

        return view('admin.vehicle-names', compact('vehicleNames'));

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

        if (VehicleName::where('name', $request->name)->exists()) {
            return back()->with('error', 'Vehicle Name Already Exists');
        }

        VehicleName::create([
            'name' => $request->name
        ]);
        
        return back()->with('success', 'Vehicle Name Added Successfully');
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
    public function update(VehicleName $vehicleName, Request $request)
    {
        //
        // dd($vehicleName->name);

        if ($vehicleName->name == $request->name) {
            return back()->with('error', 'Vehicle Name Not Updated');
        }

        $vehicleName->name = $request->name;
        $vehicleName->save();

        return back()->with('success', 'Vehicle Name Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VehicleName $vehicleName)
    {
        //
        $vehicleName->delete();

        return back()->with('success', 'Vehicle Name Deleted Successfully');
    }
}
