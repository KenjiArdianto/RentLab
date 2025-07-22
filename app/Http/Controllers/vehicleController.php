<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\UserReview;
use App\Models\Vehicle;
use App\Models\VehicleImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $listItem = Vehicle::all();
        return view('welcome', compact('listItem'));
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
        $idVehicle = Vehicle::with('vehicleCategories')->findOrFail($id);
        $getVehicleByIdsINCarts = Cart::where('user_id', 1 )->where('vehicle_id', $id)->get(); #nunggu id user hasil login, codingan di bawah
        // $getVehicleByIdsINCarts = Cart::where('user_id', auth()->id() )->where('vehicle_id', $id)->get(); 

        $getCommentByIdVehicle = UserReview::whereHas('transaction', function ($query) use ($id) {
            $query->where('vehicle_id', $id);
        })->get();

        $getVehicleimagesById = VehicleImage::where("vehicle_id","=",$id)->get();


        $cartDateRanges = $getVehicleByIdsINCarts->map(function ($item) {
            return [
                'start_date' => $item->start_date,
                'end_date' => $item->end_date,
            ];
        });

        $rating = DB::table('user_reviews')
        ->join('transactions', 'user_reviews.transaction_id', '=', 'transactions.id')
        ->join('vehicles', 'transactions.vehicle_id', '=', 'vehicles.id')
        ->select(
            DB::raw('ROUND(AVG(user_reviews.rate), 1) as average_rating')
        )
        ->where('vehicles.id', $id)
        ->groupBy('vehicles.id')
        ->first();

        return view('DetailPage', compact('rating','idVehicle', 'getVehicleByIdsINCarts', 'getCommentByIdVehicle', 'cartDateRanges', 'getVehicleimagesById'));
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
