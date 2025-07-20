<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Driver;
use App\Models\Location;
use Illuminate\Support\Facades\File;


class AdminDriverController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $drivers = Driver::with('location')->paginate(31);
        $locations = Location::all();
        return view('admin.drivers', compact('drivers', 'locations'));
    }

    public function search(Request $request)
    {
        $locations = Location::all();
        $search = $request->query('search');
        $hasValidFilters = false;

        $query = Driver::join('locations', 'drivers.location_id', '=', 'locations.id')
                    ->select('drivers.*', 'locations.location as location_location'); // avoid column conflicts

        $allowedFields = ['name', 'driver_id', 'location_id'];

        if ($search) {
            $pairs = explode(',', $search);

            foreach ($pairs as $pair) {
                if (!str_contains($pair, '=')) continue;

                [$key, $value] = array_map('trim', explode('=', $pair, 2));

                if ($key === 'location' && $value !== '') {
                    $query->where('locations.location', 'like', "%{$value}%");
                    $hasValidFilters = true;
                } elseif (in_array($key, $allowedFields) && $value !== '') {
                    if ($key === 'driver_id') {
                        $key = 'id';
                    }
                    $query->where("drivers.$key", 'like', "%{$value}%");
                    $hasValidFilters = true;
                }
            }
        }

        if (!$hasValidFilters) {
            $drivers = Driver::with('location')->paginate(31)->appends(['search' => $search]);;
        } else {
            $drivers = $query->with('location')->paginate(31)->appends(['search' => $search]);;
        }

        if ($drivers->isEmpty()) {
            return redirect()->route('admin.drivers')->with('error', 'No drivers found.');
        }

        return view('admin.drivers', compact('drivers', 'locations'));
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
        // dd($request->all());
        // Store Image

        // Storing image in public folder
        $image = $request->file('image');
        $image_name = 'driver_image_' .  time() . '_' . $image->getClientOriginalName();
        $image->move(public_path('assets'), $image_name);

        $image_path = 'assets/' . $image_name;

        // // Storing Data in Model
        Driver::create([
            'name' => $request->name,
            'image' => $image_path,
            'location_id' => $request->location_id
        ]);

        // dd($image_path);    
        return back()->with('success', 'Driver added.');
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
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function editSelected(Request $request)
    {
        // Format variable action_type dari request itu Operation_DriverID
        // Jadi disini di split dulu dengan _ sebagai separator

        list($action_type, $driver_id) = explode('_', $request->input('action_type'));

        if ($action_type == 'delete') {

            $driver = Driver::where('id', $driver_id)->first();

            File::delete(public_path($driver->image));

            $driver->delete();
            return back()->with('success', 'Selected driver deleted.');
        }
        else if ($action_type == 'edit') {

            $driver = Driver::where('id', $driver_id)->first();
            
            if($request->hasFile('image')){ 
                $image = $request->file('image');
                $image_name = 'driver_image_' .  time() . '_' . $image->getClientOriginalName();
                File::delete(public_path($driver->image));
                $image->move(public_path('assets'), $image_name);

                $image_path = 'assets/' . $image_name;

                $driver->image = $image_path;

            }
            
            if ($request->has('name')) {
                $driver->name = $request->input('name');
            }

            if ($request->has('location_id')) {
                $driver->location_id = $request->input('location_id');
            }

            // $driver->image = $request->input('image');
            $driver->save();
            
            return back()->with('success', 'Selected driver edited.');
        }
    }

    public function deleteSelected(Request $request)
    {
        $id_list = $request->input('selected', []);

        Driver::whereIn('id', $id_list)->delete();

        if (!$id_list) {
            return back();
        }

        return back()->with('success', 'Selected drivers deleted.');
    }
}
