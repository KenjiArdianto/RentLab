<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\VehicleReview;
use Illuminate\Http\Request;
use App\Http\Requests\AdminVehicleReviewRequest;

class AdminVehicleReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Vehicle $vehicle)
    {
        //
        $search = $request->query('search');
        $query = $vehicle->vehicleReview()->getQuery();

        // split search by comma
        if ($search) {
            $pairs = explode(',', $search);

            foreach ($pairs as $pair) {
                if (!str_contains($pair, '=')) continue;

                [$key, $value] = array_map('trim', explode('=', $pair, 2));
                
                // handle review_id
                if ($key === 'review_id') {
                    $query->where('id', $value);
                }
                // handle user_id
                else if ($key === 'user_id') {
                    $query->where('user_id', $value);
                }
                // handle transaction_id
                else if ($key === 'transaction_id') {
                    $query->where('transaction_id', $value);
                }
                // handle comment
                else if ($key === 'comment') {
                    $query->where('comment', 'like', "%{$value}%");
                }
                // handle rating
                else if ($key === 'rating') {
                    $query->where('rate', $value);
                }
            }
        }


        $reviews = $query->paginate(100);

        return view('admin.vehicles.reviews', compact('vehicle', 'reviews'));
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
    public function update(AdminVehicleUpdateRequest $request, Vehicle $vehicle, VehicleReview $vehicleReview)
    {
        //
        $reviewUpdated = false;

        // dd($vehicleReview->id);
        if ($vehicleReview->comment != $request->comment) {
            $reviewUpdated = true;
            $vehicleReview->comment = $request->comment;
        }

        if ($vehicleReview->rate != $request->rate) {
            $reviewUpdated = true;
            $vehicleReview->rate = $request->rate;
        }

        $vehicleReview->save();

        if (!$reviewUpdated) {
            return back()->with('error', 'Review Not Updated');
        }

        return back()->with('success', 'Review Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vehicle $vehicle, VehicleReview $vehicleReview)
    {
        //
        $vehicleReview->delete();

        return back()->with('success', 'Review Deleted Successfully');

    }
}
