<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LandingController extends Controller
{
    /**
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function search(Request $request)
    {
        $validated_data = $request->validate([
            'vehicle_type'    => 'required|string|in:motorcycle,car',
            'start_book_date' => 'required|date|after_or_equal:today',
            'end_book_date'   => 'required|date|after_or_equal:start_book_date',
        ]);

        $redirect_parameters = [
            'Tipe_Kendaraan' => $validated_data['vehicle_type'] === 'car' ? 'Car' : 'Motor',
            'start_book_date' => 'required|date_format:Y-m-d|after_or_equal:today',
            'end_book_date'   => 'required|date_format:Y-m-d|after_or_equal:start_book_date',
            'min_price'      => '',
            'max_price'      => '',
        ];
        return redirect()->route('vehicle.display', $redirect_parameters);
    }
}
