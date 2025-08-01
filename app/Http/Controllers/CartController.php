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
    //menampilkan item dalam cart
    public function index()
    {
        $userId=Auth::user()->id;
        
        $listCart = Cart::where("user_id", $userId)
                    ->orderBy('start_date', 'desc')
                    ->get();


        $today = Carbon::today();
        
        //menampilkan cart tanggal mendatang diurutkan menurut start date
        $upcomingCart = Cart::where("user_id", $userId)
                            ->where('start_date', '>=', $today)
                            ->orderBy('start_date', 'asc')
                            ->get();

        //menampilkan cart tanggal expired diurutkan menurut start date
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

        //cek apakah kendaraan exist    
        $vehicle = Vehicle::find($vehicleId);
        if (!$vehicle) {
            return back()->with('error', 'Kendaraan tidak ditemukan.');
        }

        $currentCartItemCount = Cart::where('user_id', $userId)->count();
        $itemsToAdd = count($dateRanges);

        //cek maksimal items dalam cart 
        if (($currentCartItemCount + $itemsToAdd) > 10) {
            return back()->with('error', 'Maksimal 10 item pada Cart. Anda sudah memiliki ' . $currentCartItemCount . ' item.');
        }

        foreach ($dateRanges as $range) {
            $startDate = Carbon::parse($range['start_date']);
            $endDate = Carbon::parse($range['end_date']);

            //query untuk mengambil item yang sudah ada di transaction (di pesan orang)    
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

            //menghitung diskon yang didapat oleh user 
            //maksimal mendapat 30% diskon 
            //
            $numberOfDays = $startDate->diffInDays($endDate) +1;
            $discountPercentage = min(0.05 * ($numberOfDays-1), 0.30); 
            $totalPriceBeforeDiscount = $vehicle->price * $numberOfDays;
            $subtotal = $totalPriceBeforeDiscount * (1 - $discountPercentage);

            Cart::create([
                'vehicle_id' => $vehicleId,
                'start_date' => $range['start_date'],
                'end_date' => $range['end_date'],
                'user_id' => $userId,
                'subtotal' => round($subtotal, 2), 
            ]); 
        }

        return back()->with('success', 'Tanggal berhasil ditambahkan ke keranjang!');
    }

    //Function untuk menghapus item dari cart
    public function destroy(string $id)
    {

        $cartitems=Cart::where('id',$id)->first();
        if($cartitems->user_id != Auth::id()){
            return back()->withError();
        }

        Cart::destroy($id);
        return back()->with('success', 'Item berhasil dihapus dari keranjang.');
    }

//Function untuk menghapus semua tanggal yang sudah expired
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
        $userId = Auth::id(); 
        $count = Cart::where('user_id', $userId)->count();
        return response()->json(['count' => $count]);
    }
}
