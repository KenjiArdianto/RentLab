<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminIndexController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $logCount              = \App\Models\Log::count();
        $userCount             = \App\Models\User::count();
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
            [
                'title' => __('admin_tables.users'),
                'count' => $userCount,
                'route' => route('admin.users'),
                'color' => 'secondary'
            ],
            [
                'title' => __('admin_tables.drivers'),
                'count' => $driverCount,
                'route' => route('admin.drivers'),
                'color' => 'success'
            ],
            [
                'title' => __('admin_tables.transactions'),
                'count' => $transactionCount,
                'route' => route('admin.transactions'),
                'color' => 'info'
            ],
            [
                'title' => __('admin_tables.vehicles'),
                'count' => $vehicleCount,
                'route' => route('admin.vehicles'),
                'color' => 'warning'
            ],
            [
                'title' => __('admin_tables.vehicle_types'),
                'count' => $vehicleTypeCount,
                'route' => route('admin.vehicle-types'),
                'color' => 'dark'
            ],
            [
                'title' => __('admin_tables.vehicle_names'),
                'count' => $vehicleNameCount,
                'route' => route('admin.vehicle-names'),
                'color' => 'primary'
            ],
            [
                'title' => __('admin_tables.vehicle_transmissions'),
                'count' => $vehicleTransmissionCount,
                'route' => route('admin.vehicle-transmissions'),
                'color' => 'secondary'
            ],
            [   'title' => __('admin_tables.vehicle_categories'),
                'count' => $vehicleCategoryCount,
                'route' => route('admin.vehicle-categories'),
                'color' => 'success'
            ],
            [
                'title' => __('admin_tables.locations'),
                'count' => $locationCount,
                'route' => route('admin.locations'),
                'color' => 'info'
            ],
        ];
        \activity('admin_index')
        ->causedBy(Auth::user())
        ->withProperties([
            'ip' => request()->ip(),
            'object_counts' => [
                'users' => $userCount,
                'drivers' => $driverCount,
                'transactions' => $transactionCount,
                'vehicles' => $vehicleCount,
                'vehicle_types' => $vehicleTypeCount,
                'vehicle_names' => $vehicleNameCount,
                'vehicle_transmissions' => $vehicleTransmissionCount,
                'vehicle_categories' => $vehicleCategoryCount,
                'locations' => $locationCount,
            ],
            'user_agent' => request()->userAgent(),
        ])
        ->log('Admin accessed the dashboard');

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
