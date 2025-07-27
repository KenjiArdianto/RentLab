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

        $search = $request->query('search');

        // split search by comma
        if ($search) {
            $pairs = explode(',', $search);

            foreach ($pairs as $pair) {
                if (!str_contains($pair, '=')) continue;

                [$key, $value] = array_map('trim', explode('=', $pair, 2));
                
                // handle user_id
                if ($key === 'user_id' || $key === 'id_pengguna') {
                    $query->where('id', $value);
                }
                // handle username
                else if ($key === 'username' || $key === 'nama_pengguna') {
                    $query->where('name', 'like', '%' . $value . '%');
                }
                // handle email
                else if ($key === 'email') {
                    $query->whereHas('detail', function ($q) use ($value) {
                        $q->where('email', 'like', '%' . $value . '%');
                    });
                }
                // handle first_name
                else if ($key === 'first_name' || $key === 'nama_depan') {
                    $query->whereHas('detail', function ($q) use ($value) {
                        $q->where('fname', 'like', '%' . $value . '%');
                    });
                }
                // handle last_name
                else if ($key === 'last_name' || $key === 'nama_belakang') {
                    $query->whereHas('detail', function ($q) use ($value) {
                        $q->where('lname', 'like', '%' . $value . '%');
                    });
                }
                // handle phone_number
                else if ($key === 'phone_number' || $key === 'nomor_hp') {
                    $query->whereHas('detail', function ($q) use ($value) {
                        $q->where('phoneNumber', 'like', '%' . $value . '%');
                    });
                }  
                // handle idcard_number
                else if ($key === 'idcard_number' || $key === 'nik') {
                    $query->whereHas('detail', function ($q) use ($value) {
                        $q->where('idcardNumber', 'like', '%' . $value . '%');
                    });
                }
                // handle dob
                else if ($key === 'dob' || $key === 'tanggal_lahir') {
                    $dateParts = explode('-', $value);

                    if (count($dateParts) === 3) {
                        // yyyy-mm-dd
                        $query->whereHas('detail', function ($q) use ($value) {
                            $q->where('dateOfBirth', $value);
                        });
                    } elseif (count($dateParts) === 2) {
                        // yyyy-mm
                        [$year, $month] = explode('-', $value);
                        $query->whereHas('detail', function ($q) use ($value) {
                            $q->whereYear('dateOfBirth',$year)->whereMonth('dateOfBirth', $month);
                        });
                    } elseif (count($dateParts) === 1) {
                        // yyyy
                        $query->whereHas('detail', function ($q) use ($value) {
                            $q->whereYear('dateOfBirth',$value);
                        });
                    }
                }
                // handle rating
                else if ($key === 'rating' || $key === 'penilaian') {
                    $query->whereHas('reviews', function ($q) use ($value) {
                        $q->selectRaw('user_id, AVG(rate) as avg_rating')
                        ->groupBy('user_id')
                        ->havingRaw('FLOOR(avg_rating) = ?', [(int)$value]);
                    });
                }
                // handle suspended_at
                else if ($key === 'suspended_at' || $key === 'ditangguhkan_sejak') {
                    $dateParts = explode('-', $value);

                    if (count($dateParts) === 3) {
                        // yyyy-mm-dd
                        $query->where('suspended_at', $value);
                    } elseif (count($dateParts) === 2) {
                        // yyyy-mm
                        [$year, $month] = explode('-', $value);
                        $query->whereYear('suspended_at',$year)->whereMonth('suspended_at', $month);
                    } elseif (count($dateParts) === 1) {
                        // yyyy
                        $query->whereYear('suspended_at',$value);
                    }
                }
                // handle deleted_at
                else if ($key === 'deleted_at' || $key === 'dihapus_sejak') {
                    $dateParts = explode('-', $value);

                    if (count($dateParts) === 3) {
                        // yyyy-mm-dd
                        $query->where('deleted_at', $value);
                    } elseif (count($dateParts) === 2) {
                        // yyyy-mm
                        [$year, $month] = explode('-', $value);
                        $query->whereYear('deleted_at',$year)->whereMonth('deleted_at', $month);
                    } elseif (count($dateParts) === 1) {
                        // yyyy
                        $query->whereYear('deleted_at',$value);
                    }
                }
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
