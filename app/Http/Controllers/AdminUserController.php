<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query()->with(['detail', 'reviews'])->where('role', '!=', 'admin');

        // Apply filter based on status
        if ($request->has('filter')) {
            switch ($request->filter) {
                case 'active':
                    $query->whereNull('suspended_at')->whereNull('deleted_at');
                    break;
                case 'suspended':
                    $query->whereNotNull('suspended_at')->whereNull('deleted_at');
                    break;
                case 'deleted':
                    $query->onlyTrashed(); // Make sure you are using SoftDeletes in User model
                    break;
            }
        }

        $users = $query->paginate(33);

        return view('admin.users', compact('users'));
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
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }

    public function suspendSelected(Request $request)
    {
        //
        
        $ids = $request->selected;
        // dd($ids);
        User::whereIn('id', $ids)->update(['suspended_at' => now()]);
        return back()->with('success', 'Users Suspended Successfully');
    }

    public function suspend(User $user)
    {
        //
        
        $user->update(['suspended_at' => now()]);

        return back()->with('success', 'User Suspended Successfully');
    }

    public function unsuspendSelected(Request $request)
    {
        //
        
        $ids = $request->selected;
        // dd($ids);
        User::whereIn('id', $ids)->update(['suspended_at' => null]);
        return back()->with('success', 'Users Unsuspended Successfully');
    }

    public function unsuspend(User $user)
    {
        //
        // dd($user);
        $user->update(['suspended_at' => null]);

        return back()->with('success', 'User Unsuspended Successfully');
    }
}
