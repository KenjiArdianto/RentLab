<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\VehicleReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AdminVehicleUpdateRequest;

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

        \activity('admin_review_search')
        ->causedBy(Auth::user())
        ->withProperties([
            'ip' => $request->ip(),
            'vehicle_id' => $vehicle->id,
            'search_query' => $search,
            'user_agent' => $request->userAgent(),
        ])
        ->log("Admin searched reviews for vehicle #$vehicle->id with query: $search");
        $reviews = $query->paginate(100)->appends(['search' => $search]);

        return view('admin.vehicles.reviews', compact('vehicle', 'reviews'));
    }
}
