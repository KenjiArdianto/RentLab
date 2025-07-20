<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Vehicle;
use App\Models\VehicleCategory;
use App\Models\VehicleType;
use App\Models\VehicleName;
use App\Models\VehicleTransmission;
use Illuminate\Http\Request;

class AdminVehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $vehicles = Vehicle::query()
            ->with(['vehicleName', 'vehicleType', 'vehicleTransmission', 'vehicleCategories', 'location', 'vehicleReview', 'transactions', 'vehicleImages'])
            ->paginate(15);

        $vehicleTypes = VehicleType::all();
        $vehicleNames = VehicleName::all();
        $vehicleTransmissions = VehicleTransmission::all();
        $locations = Location::all();
        $categories = VehicleCategory::all();
        // dd($categories->all());
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
    public function store(Request $request)
    {
        //
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
    public function update(Request $request, string $id)
    {
        //
        dd($request->all());

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
