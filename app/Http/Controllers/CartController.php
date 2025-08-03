<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use  Carbon\Carbon;
use App\Http\Requests\StoreCartRequest;
use App\Models\Vehicle;
use App\Models\Transaction;


class CartController extends Controller
{
    //show cart item
    public function index()
    {
        $userId=Auth::user()->id;

        $listCart = Cart::where("user_id", $userId)
                    ->orderBy('start_date', 'desc')
                    ->get();

        $today = Carbon::today();
        
        //show upcomming cart ordered by start date
        $upcomingCart = Cart::where("user_id", $userId)
                            ->where('start_date', '>=', $today)
                            ->orderBy('start_date', 'asc')
                            ->get();

        //show outdated cart ordered by start date
        $outdatedCart = Cart::where("user_id", $userId)
                            ->where('start_date', '<', $today)
                            ->orderBy('start_date', 'desc')
                            ->get();
        \activity('cart_index')
        ->causedBy(Auth::user())
        ->withProperties([
            'ip'=>request()->ip(),
            'cart_shown' => [
                'upcoming' => $upcomingCart->pluck('id')->toArray(),
                'outdated' => $outdatedCart->pluck('id')->toArray(),
            ],
            'user_agent' => request()->userAgent(),
        ])
        ->log('User viewed their cart.');

        return view("CartPage", compact('listCart', 'upcomingCart', 'outdatedCart'));
    }

    public function store(StoreCartRequest $request)
    {

        $vehicleId = $request->input('vehicle_id');
        $dateRanges = $request->input('date_ranges');
        $userId=Auth::id();

        $vehicle = Vehicle::find($vehicleId);
        if (!$vehicle) {
            \activity('cart')
            ->causedBy(Auth::user())
            ->withProperties([
                'ip' => request()->ip(),
                'vehicle_id' => $vehicleId,
                'user_agent' => request()->userAgent(),
            ])
            ->log('Failed to add cart: vehicle not found');
            return back()->with('error', 'Kendaraan tidak ditemukan.');
        }

        $currentCartItemCount = Cart::where('user_id', $userId)->count();
        $itemsToAdd = count($dateRanges);

        //maximal item cart check
        if (($currentCartItemCount + $itemsToAdd) > 10) {
            return back()->with('error', 'Maksimal 10 item pada Cart. Anda sudah memiliki ' . $currentCartItemCount . ' item.');
        }

        foreach ($dateRanges as $range) {
            $startDate = Carbon::parse($range['start_date']);
            $endDate = Carbon::parse($range['end_date']);

            //query for cart items that booked by other user  
            $bookedTransactionDates = Transaction::where('vehicle_id', $vehicleId)
            ->where('transaction_status_id', '<', 7) 
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_book_date', [$startDate, $endDate])
                      ->orWhereBetween('end_book_date', [$startDate, $endDate])
                      ->orWhere(function ($query) use ($startDate, $endDate) {
                          $query->where('start_book_date', '<', $startDate)
                                ->where('end_book_date', '>', $endDate);
                      });
            })->get(); 

            if ($bookedTransactionDates->count() > 0) {
                return back()->with('error', 'Rentang tanggal ' . $startDate->format('d M Y') . ' - ' . $endDate->format('d M Y') . ' tumpang tindih dengan pemesanan lain atau sudah di keranjang. Silakan pilih tanggal lain.');
            }

            //calculating discount
            //maximal 30% dscount
            $numberOfDays = $startDate->diffInDays($endDate) +1;
            $discountPercentage = min(0.05 * ($numberOfDays-1), 0.30); 
            $totalPriceBeforeDiscount = $vehicle->price * $numberOfDays;
            $subtotal = $totalPriceBeforeDiscount * (1 - $discountPercentage);

            $cart=Cart::create([
                'vehicle_id' => $vehicleId,
                'start_date' => $range['start_date'],
                'end_date' => $range['end_date'],
                'user_id' => $userId,
                'subtotal' => round($subtotal, 2), 
            ]); 
            \activity('cart')
            ->performedOn($cart)
            ->causedBy(Auth::user())
            ->withProperties([
                'ip' => request()->ip(),
                'vehicle_id' => $vehicleId,
                'start_date' => $range['start_date'],
                'end_date' => $range['end_date'],
                'subtotal' => round($subtotal),
            ])
            ->log('Cart item created');
        }

        return back()->with('success', 'Tanggal berhasil ditambahkan ke keranjang!');
    }

    //delete items from cart
    public function destroy(string $id)
    {

        $cartitems=Cart::where('id',$id)->first();
        if(!$cartitems){
            \activity('cart')
            ->causedBy(Auth::user())
            ->withProperties([
                'ip' => request()->ip(),
                'cart_id' => $id,
            ])
            ->log('Failed to delete cart: item not found');
            return back()->withErrors(['error' => 'Cart item not found.']);
        }
        if($cartitems->user_id != Auth::id()){
            \activity('cart')
            ->performedOn($cartitems)
            ->causedBy(Auth::user())
            ->withProperties([
                'ip' => request()->ip(),
                'cart_id' => $id,
            ])
            ->log('Unauthorized cart delete attempt');
            return back()->with('error', 'Anda tidak memiliki hak untuk menghapus item ini.');
        }

        Cart::destroy($id);
        \activity('cart')
        ->performedOn($cartitems)
        ->causedBy(Auth::user())
        ->withProperties([
            'ip' => request()->ip(),
            'cart_id' => $id,
        ])
        ->log('Cart item deleted');
        return back()->with('success', 'Item berhasil dihapus dari keranjang.');
    }

    //delete all expired cart items
    public function clearOutdated()
    {
        $today = Carbon::today();
        $deletedCart=Cart::where('user_id', Auth::id())
            ->where('start_date', '<', $today)
            ->delete();
        \activity('cart')
        ->causedBy(Auth::user())
        ->withProperties([
            'ip' => request()->ip(),
            'deleted_count' => $deletedCart,
        ])
        ->log('User cleared outdated cart items');
        return back()->with('success', 'Semua rental kedaluwarsa berhasil dihapus!');
    }

    public function getCartItemCount()
    {
        $userId = Auth::id(); 
        $count = Cart::where('user_id', $userId)->count();
        \activity('cart')
        ->causedBy(Auth::user())
        ->withProperties([
            'ip' => request()->ip(),
            'item_count' => $count,
        ])
        ->log('User checked cart item count');
        return response()->json(['count' => $count]);
    }
}
