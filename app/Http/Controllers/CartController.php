<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use  Carbon\Carbon;
use App\Http\Requests\StoreCartRequest;
use App\Models\Vehicle;

class CartController extends Controller
{
    /**
     * Menampilkan daftar sumber daya.
     */
    public function index()
    {
        $userId=Auth::user()->id;
        $listCart = Cart::where("user_id", $userId)
                    ->orderBy('start_date', 'desc')
                    ->get();


        $today = Carbon::today();

        $upcomingCart = Cart::where("user_id", $userId)
                            ->where('start_date', '>=', $today)
                            ->orderBy('start_date', 'asc')
                            ->get();


        $outdatedCart = Cart::where("user_id", $userId)
                            ->where('start_date', '<', $today)
                            ->orderBy('start_date', 'desc')
                            ->get();

        return view("CartPage", compact('listCart', 'upcomingCart', 'outdatedCart'));
    }

    public function store(StoreCartRequest $request)
    {

        $vehicleId = $request->input('vehicle_id');
        $dateRanges = $request->input('date_ranges');
        $userId=Auth::id();

        // Fetch the vehicle to get its price
        $vehicle = Vehicle::find($vehicleId);
        if (!$vehicle) {
            return back()->with('error', 'Kendaraan tidak ditemukan.');
        }

        $currentCartItemCount = Cart::where('user_id', $userId)->count();
        $itemsToAdd = count($dateRanges);

        if (($currentCartItemCount + $itemsToAdd) > 10) {
            return back()->with('error', 'Maksimal 10 item pada Cart. Anda sudah memiliki ' . $currentCartItemCount . ' item.');
        }

        foreach ($dateRanges as $range) {
            $startDate = Carbon::parse($range['start_date']);
            $endDate = Carbon::parse($range['end_date']);


            // Calculate the number of days
            $numberOfDays = $startDate->diffInDays($endDate) +1;

            // Calculate the discount percentage
            // 5% discount per day, capped at 30% (which means max 6 days for discount)
            $discountPercentage = min(0.05 * ($numberOfDays-1), 0.30); // 0.05 = 5%, 0.30 = 30%

            // Calculate the total price before discount
            $totalPriceBeforeDiscount = $vehicle->price * $numberOfDays;

            // Calculate the subtotal with discount
            $subtotal = $totalPriceBeforeDiscount * (1 - $discountPercentage);

            // dd($subtotal);

            Cart::create([
                'vehicle_id' => $vehicleId,
                'start_date' => $range['start_date'],
                'end_date' => $range['end_date'],
                'user_id' => $userId,
                'subtotal' => round($subtotal), // Store the calculated subtotal
            ]);
        }

        return back()->with('success', 'Tanggal berhasil ditambahkan ke keranjang!');
    }
    public function destroy(string $id)
    {
        $cartitems=Cart::where('id',$id)->get();
        if($cartitems->user_id != Auth::id()){
            return back()->withError();
        }
        Cart::destroy($id);
        return back()->with('success', 'Item berhasil dihapus dari keranjang.');
    }

    public function clearOutdated()
    {
        $today = Carbon::today();
        Cart::where('user_id', Auth::id())
            ->where('start_date', '<', $today)
            ->delete();

        return back()->with('success', 'Semua rental kedaluwarsa berhasil dihapus!');
    }

    public function getCartItemCount()
    {
        $userId = Auth::id() ; // Gunakan ID pengguna yang terautentikasi, atau dummy untuk pengujian
        $count = Cart::where('user_id', $userId)->count();
        return response()->json(['count' => $count]);
    }
}
