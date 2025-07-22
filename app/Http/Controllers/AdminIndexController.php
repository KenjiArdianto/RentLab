<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminIndexController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $logCount              = \App\Models\Log::count();
        // $userCount             = \App\Models\User::count();
        $driverCount           = \App\Models\Driver::count();
        $transactionCount      = \App\Models\Transaction::count();
        $vehicleCount          = \App\Models\Vehicle::count();
        $vehicleTypeCount      = \App\Models\VehicleType::count();
        $vehicleNameCount      = \App\Models\VehicleName::count();
        $vehicleTransmissionCount = \App\Models\VehicleTransmission::count();
        $vehicleCategoryCount  = \App\Models\VehicleCategory::count();
        $locationCount         = \App\Models\Location::count();
        
        $counts = [
        // [
        //     'title' => 'Logs',
        //     'count' => $logCount,
        //     'route' => route('admin.logs'),
        //     'label' => 'Logs',
        //     'color' => 'primary'
        // ],
        // [
        //     'title' => 'Users',
        //     'count' => $userCount,
        //     'route' => route('admin.users'),
        //     'label' => 'Users',
        //     'color' => 'secondary'
        // ],
        [
            'title' => 'Drivers',
            'count' => $driverCount,
            'route' => route('admin.drivers'),
            'label' => 'Drivers',
            'color' => 'success'
        ],
        [
            'title' => 'Transactions',
            'count' => $transactionCount,
            'route' => route('admin.transactions'),
            'label' => 'Transactions',
            'color' => 'info'
        ],
        [
            'title' => 'Vehicles',
            'count' => $vehicleCount,
            'route' => route('admin.vehicles'),
            'label' => 'Vehicles',
            'color' => 'warning'
        ],
        [
            'title' => 'Vehicle Types',
            'count' => $vehicleTypeCount,
            'route' => route('admin.vehicle-types'),
            'label' => 'Types',
            'color' => 'dark'
        ],
        [
            'title' => 'Vehicle Names',
            'count' => $vehicleNameCount,
            'route' => route('admin.vehicle-names'),
            'label' => 'Names',
            'color' => 'primary'
        ],
        [
            'title' => 'Vehicle Transmissions',
            'count' => $vehicleTransmissionCount,
            'route' => route('admin.vehicle-transmissions'),
            'label' => 'Transmissions',
            'color' => 'secondary'
        ],
        ['title' => 'Vehicle Categories',
            'count' => $vehicleCategoryCount,
            'route' => route('admin.vehicle-categories'),
            'label' => 'Categories',
            'color' => 'success'
        ],
        [
            'title' => 'Locations',
            'count' => $locationCount,
            'route' => route('admin.locations'),
            'label' => 'Locations',
            'color' => 'info'
        ],
    ];

        return view('admin.index', compact('counts'));
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
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
