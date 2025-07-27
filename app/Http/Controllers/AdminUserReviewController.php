<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminUserReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, User $user)
    {
        //
        $search = $request->query('search');
        $query = $user->reviews()->getQuery();

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
                // handle admin_id
                else if ($key === 'admin_id') {
                    $query->where('admin_id', $value);
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

        return view('admin.users.reviews', compact('user', 'reviews'));
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
