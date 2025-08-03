<?php

namespace App\Http\Controllers;

use App\Models\VehicleCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        $vehicleCategories = $vehicleCategories->paginate(100)->appends(['search' => $search]);

        \activity('admin_vehicle_category_index')
        ->causedBy(Auth::user())
        ->withProperties([
            'ip' => $request->ip(),
            'search_query' => $request->query('search'),
            'result_count' => $vehicleCategories->total(),
            'user_agent' => $request->userAgent(),
        ])
        ->log('Admin viewed vehicle category list' . ($request->has('search') ? ' with filters' : ''));


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
            \activity('admin_vehicle_category_store_duplicate')
            ->causedBy(Auth::user())
            ->withProperties([
                'ip' => $request->ip(),
                'attempted_category' => $request->category,
                'user_agent' => $request->userAgent(),
            ])
            ->log('Attempted to add duplicate vehicle category');

            return back()->with('error', 'Vehicle Category Already Exists');
        }

        $newCategory=VehicleCategory::create([
            'category' => $request->category
        ]);

        \activity('admin_vehicle_category_store')
        ->causedBy(Auth::user())
        ->performedOn($newCategory)
        ->withProperties([
            'ip' => $request->ip(),
            'category_added' => $request->category,
            'user_agent' => $request->userAgent(),
        ])
        ->log('Added new vehicle category');

        
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
            \activity('admin_vehicle_category_update_unchanged')
            ->causedBy(Auth::user())
            ->performedOn($vehicleCategory)
            ->withProperties([
                'ip' => $request->ip(),
                'category_name' => $vehicleCategory->category,
                'user_agent' => $request->userAgent(),
            ])
            ->log('Attempted to update vehicle category but no changes were made');

            return back()->with('error', 'Vehicle Category Not Updated');
        }

        $vehicleCategory->category = $request->category;
        $vehicleCategory->save();

        \activity('admin_vehicle_category_update')
        ->causedBy(Auth::user())
        ->performedOn($vehicleCategory)
        ->withProperties([
            'ip' => $request->ip(),
            'old_category' => $vehicleCategory->getOriginal('category'),
            'new_category' => $request->category,
            'user_agent' => $request->userAgent(),
        ])
        ->log('Updated vehicle category');


        return back()->with('success', 'Vehicle Category Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VehicleCategory $vehicleCategory)
    {
        //
        \activity('admin_vehicle_category_destroy')
        ->causedBy(Auth::user())
        ->performedOn($vehicleCategory)
        ->withProperties([
            'ip' => request()->ip(),
            'deleted_category' => $vehicleCategory->category,
            'user_agent' => request()->userAgent(),
        ])
        ->log('Deleted vehicle category');

        $vehicleCategory->delete();

        return back()->with('success', 'Vehicle Category Deleted Successfully');
    }
}
