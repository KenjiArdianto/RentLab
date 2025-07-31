<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class AdminLocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $locations = Location::query();


        $search = request('search');
        if ($search) {
            $locations->where('location', 'like', '%' . $search . '%');
        }

        $locations = $locations->paginate(100);

        return view('admin.locations', compact('locations'));

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

        if (Location::where('location', $request->location)->exists()) {
            return back()->with('error', 'Location Already Exists');
        }

        Location::create([
            'location' => $request->location
        ]);
        
        return back()->with('success', 'Location Added Successfully');
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
    public function update(Request $request, Location $location)
    {
        //
        // dd($location);

        if ($location->location === $request->location) {
            return back()->with('error', 'Location Not Updated');
        }

        $location->location = $request->location;
        $location->save();

        return back()->with('success', 'Location Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Location $location)
    {
        //
        $location->delete();

        return back()->with('success', 'Location Deleted Successfully');
    }
}
