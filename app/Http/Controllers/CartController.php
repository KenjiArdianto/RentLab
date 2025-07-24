<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        // $listCart = Cart::all();
        // return view("CartPage", compact('listCart'));

        // TUNGGU ADA LOGIN ID BARU BIS PAKE sementara nembak dulu
        $listCart = Cart::where("user_id", 1)->get(); 
        return view("CartPage", compact('listCart'));


    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('CartPage');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request){
    $vehicleId = $request->input('vehicle_id');
    $dateRanges = $request->input('date_ranges');
    // $dummyId = $request->input('user_id');

    foreach ($dateRanges as $range) {
        Cart::create([
            'vehicle_id' => $vehicleId,
            'start_date' => $range['start_date'],
            'end_date' => $range['end_date'],
        //    'user_id' => auth()->id(),
        //    'user_id' => $dummyId,
        ]);
    }

    return redirect("/Cartpage");
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
    public function destroy(Request $request, string $id)
    {
        //
        Cart::destroy($id);
        $vehicleId = $request->input('vehicle_id');
        // return redirect("/DetailPage/{$vehicleId}");
        return back();
        
    }
    // public function hapus(Request $request, string $id)
    // {
    //     //
    //     Cart::destroy($id);
    //     return redirect("/CartPage");
        
    // }


}
