<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SearchVehicleRequest;

class LandingController extends Controller
{
    /*
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $search_data = $request->all();

        return view('landing', [
            'search_data' => $search_data
        ]);
    }

    /**
     * @param  \App\Http\Requests\SearchVehicleRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function search(SearchVehicleRequest $request)
    {
        $validated_data = $request->validated();

        $redirect_parameters = [
            'vehicle_type'    => $validated_data['vehicle_type'] === 'car' ? 'Car' : 'Motor',
            'start_book_date' => $validated_data['start_book_date'],
            'end_book_date'   => $validated_data['end_book_date'],
            'min_price'       => '',
            'max_price'       => '',
        ];
        
        return redirect()->route('vehicle.display', $redirect_parameters);
    }
}