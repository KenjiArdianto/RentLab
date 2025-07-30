<?php

namespace App\Http\Controllers;

use App\Models\VehicleCategory;
use Illuminate\Http\Request;

class AdminVehicleCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $vehicleCategories = VehicleCategory::query();


        $search = request('search');
        if ($search) {
            $vehicleCategories->where('category', 'like', '%' . $search . '%');
        }

        $vehicleCategories = $vehicleCategories->paginate(100);

        return view('admin.vehicle-categories', compact('vehicleCategories'));

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

        if (VehicleCategory::where('category', $request->category)->exists()) {
            return back()->with('error', 'Vehicle Category Already Exists');
        }

        VehicleCategory::create([
            'category' => $request->category
        ]);
        
        return back()->with('success', 'Vehicle Category Added Successfully');
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
    public function update(Request $request, VehicleCategory $vehicleCategory)
    {
        //
        // dd($vehicleCategory);

        if ($vehicleCategory->category === $request->category) {
            return back()->with('error', 'Vehicle Category Not Updated');
        }

        $vehicleCategory->category = $request->category;
        $vehicleCategory->save();

        return back()->with('success', 'Vehicle Category Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VehicleCategory $vehicleCategory)
    {
        //
        $vehicleCategory->delete();

        return back()->with('success', 'Vehicle Category Deleted Successfully');
    }
}
