<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserReview;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AdminUserReviewRequest;

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
                if ($key === 'review_id' || $key === 'id_ulasan') {
                    $query->where('id', $value);
                }
                // handle admin_id
                else if ($key === 'admin_id' || $key === 'id_admin') {
                    $query->where('admin_id', $value);
                }
                // handle transaction_id
                else if ($key === 'transaction_id' || $key === 'id_transaksi') {
                    $query->where('transaction_id', $value);
                }
                // handle comment
                else if ($key === 'comment' || $key === 'komentar') {
                    $query->where('comment', 'like', "%{$value}%");
                }
                // handle rating
                else if ($key === 'rating' || $key === 'penilaian') {
                    $query->where('rate', $value);
                }
            }
        }


        $reviews = $query->paginate(100);

        \activity('admin_user_review_index')
        ->causedBy(Auth::user())
        ->performedOn($user)
        ->withProperties([
            'ip' => $request->ip(),
            'searched_user_id' => $user->id,
            'search_query' => $request->query('search'),
            'result_count' => $reviews->total(),
            'user_agent' => $request->userAgent(),
        ])
        ->log("Admin viewed reviews of user #{$user->id}" . ($request->has('search') ? ' with filters' : ''));


        return view('admin.users.reviews', compact('user', 'reviews'));
    }

    public function update(AdminUserReviewRequest $request, User $user, UserReview $userReview)
    {
        //
        $reviewUpdated = false;
        $changes = [];

        // dd($userReview->id);
        if ($userReview->comment != $request->comment) {
            $changes['comment'] = [
                'old' => $userReview->comment,
                'new' => $request->comment,
            ];
            $reviewUpdated = true;
            $userReview->comment = $request->comment;
        }

        if ($userReview->rate != $request->rate) {
            $changes['rate'] = [
                'old' => $userReview->rate,
                'new' => $request->rate,
            ];
            $reviewUpdated = true;
            $userReview->rate = $request->rate;
        }

        
        if (!$reviewUpdated) {
            \activity('admin_review_update_failed')
            ->causedBy(Auth::user())
            ->performedOn($userReview)
            ->withProperties([
                'ip' => $request->ip(),
                'user_id' => $user->id,
                'review_id' => $userReview->id,
                'reason' => 'no changes detected',
                'user_agent' => $request->userAgent(),
            ])
            ->log("Admin tried to update review #$userReview->id for user #$user->id with no changes");
            return back()->with('error', 'Review Not Updated');
        }

        $userReview->save();

        \activity('admin_review_update')
        ->causedBy(Auth::user())
        ->performedOn($userReview)
        ->withProperties([
            'ip' => $request->ip(),
            'user_id' => $user->id,
            'review_id' => $userReview->id,
            'changes' => $changes,
            'user_agent' => $request->userAgent(),
        ])
        ->log("Admin updated review #$userReview->id for user #$user->id");

        return back()->with('success', 'Review Updated Successfully');
    }

    public function destroy(User $user, UserReview $userReview)
    {
        //
        \activity('admin_review_delete')
        ->causedBy(Auth::user())
        ->performedOn($userReview)
        ->withProperties([
            'ip' => request()->ip(),
            'user_id' => $user->id,
            'review_id' => $userReview->id,
            'user_agent' => request()->userAgent(),
        ])
        ->log("Admin deleted review #$userReview->id for user #$user->id");
        $userReview->delete();

        return back()->with('success', 'Review Deleted Successfully');

    }
}
