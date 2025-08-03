<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLocationController extends Controller
{
    public function index(Request $request)
    {
        
        $locations = Location::query();

        // handle search
        $search = request('search');
        if ($search) {
            $locations->where('location', 'like', '%' . $search . '%');
        }

        // logging
        $locations = $locations->paginate(100)->appends(['search' => $search]);
        \activity('admin_location_index')
        ->causedBy(Auth::user())
        ->withProperties([
            'ip' => $request->ip(),
            'search_query' => $request->query('search'),
            'user_agent' => $request->userAgent(),
        ])
        ->log('Admin viewed location list' . ($request->has('search') ? ' with filters' : ''));

        return view('admin.locations', compact('locations'));

    }

    public function store(Request $request)
    {
        // turn back and log if attempt to create an existing entry

        if (Location::where('location', $request->location)->exists()) {
            \activity('admin_location_created_fail')
            ->causedBy(Auth::user())
            ->withProperties([
                'ip' => $request->ip(),
                'attempted_location' => $request->location,
                'user_agent' => $request->userAgent(),
            ])
            ->log('Admin attempted to create a duplicate location');
            return back()->with('error', 'Location Already Exists');
        }

        // create new entry
        Location::create([
            'location' => $request->location
        ]);
        // logging
        \activity('admin_location_created_succssful')
        ->causedBy(Auth::user())
        ->withProperties([
            'ip' => $request->ip(),
            'location_name' => $request->location,
            'user_agent' => $request->userAgent(),
        ])
        ->log('Admin created a new location');
        
        return back()->with('success', 'Location Added Successfully');
    }

    public function update(Request $request, Location $location)
    {
        //
        // dd($location);

        // turn back and log on attempt to create existing
        if ($location->location === $request->location) {
            \activity('admin_location_update_failed')
            ->causedBy(Auth::user())
            ->performedOn($location)
            ->withProperties([
                'ip' => $request->ip(),
                'location_name' => $location->location,
                'reason' => 'Same name submitted',
                'user_agent' => $request->userAgent(),
            ])
            ->log('Admin attempted to update location but no change was made');
            return back()->with('error', 'Location Not Updated');
        }

        // update entry
        $location->location = $request->location;
        $location->save();
        // logging
        \activity('admin_location_update_successful')
        ->causedBy(Auth::user())
        ->performedOn($location)
        ->withProperties([
            'ip' => $request->ip(),
            'old_location' => $location->getOriginal('location'),
            'new_location' => $request->location,
            'user_agent' => $request->userAgent(),
        ])
        ->log('Admin updated a location');

        return back()->with('success', 'Location Updated Successfully');
    }

    public function destroy(Location $location)
    {
        // logging
        \activity('admin_location_deleted')
        ->causedBy(Auth::user())
        ->performedOn($location)
        ->withProperties([
            'ip' => request()->ip(),
            'location_name' => $location->location,
            'user_agent' => request()->userAgent(),
        ])
        ->log('Admin deleted a location');
        // delete
        $location->delete();

        return back()->with('success', 'Location Deleted Successfully');
    }
}
