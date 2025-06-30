<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function display(Request $request)
    {
        // 1. Mulai dengan query builder, bukan langsung get() atau paginate()
        $query = Vehicle::query();

        // 2. Terapkan filter satu per satu jika ada inputnya

        // Filter berdasarkan Tipe Kendaraan (Mobil/Motor)
        if ($request->filled('Tipe_Kendaraan')) {
            // Asumsi di database Anda ada kolom 'type' atau sejenisnya
            $query->where('type', $request->input('Tipe_Kendaraan'));
        }

        // Filter berdasarkan Jenis Kendaraan (SUV, MPV, dll) - ini adalah array dari checkbox
        if ($request->filled('Jenis_Kendaraan')) {
            // Asumsi nama kolomnya 'category'
            $query->whereIn('vehicle_category', $request->input('Jenis_Kendaraan'));
        }

        // Filter berdasarkan Jenis Transmisi (Manual, Matic, dll) - ini juga array
        if ($request->filled('Jenis_Transmisi')) {
            // Asumsi nama kolomnya 'transmission'
            $query->whereIn('transmission_type', $request->input('Jenis_Transmisi'));
        }

        // Filter berdasarkan Lokasi (Tempat)
        if ($request->filled('Tempat')) {
             // Asumsi nama kolomnya 'location'
            $query->whereIn('vehicle_location', $request->input('Tempat'));
        }

        // Filter berdasarkan Jangkauan Harga
        if ($request->filled('min_price')) {
            // Asumsi nama kolomnya 'price_per_day'
            $query->where('price', '>=', $request->input('min_price'));
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->input('max_price'));
        }

        // Filter berdasarkan Ketersediaan Tanggal (Ini yang paling kompleks)
        // if ($request->filled('start_date') && $request->filled('end_date')) {
        //     $startDate = $request->input('start_date');
        //     $endDate = $request->input('end_date');

        //     // Cari kendaraan yang TIDAK MEMILIKI booking yang tumpang tindih dengan rentang tanggal yang dipilih.
        //     // Ini membutuhkan relasi 'bookings' di model Vehicle.
        //     $query->whereDoesntHave('bookings', function ($q) use ($startDate, $endDate) {
        //         $q->where(function ($subQuery) use ($startDate, $endDate) {
        //             // Kondisi tumpang tindih:
        //             // 1. Booking dimulai sebelum rentang kita berakhir DAN berakhir setelah rentang kita dimulai.
        //             $subQuery->where('start_date', '<', $endDate)
        //                      ->where('end_date', '>', $startDate);
        //         });
        //     });
        // }


        // 3. Setelah semua filter diterapkan, eksekusi query dengan pagination
        // withQueryString() PENTING agar link pagination tetap membawa parameter filter
        $vehicle = $query->orderBy('id')->paginate(12)->withQueryString();

        // Ambil data iklan (ini tidak berubah)
        $advertisement = Advertisement::orderBy('id')->where('isactive', true)->get();

        // 4. Kirim data yang sudah difilter ke view
        return view('webview.homescreen', ["vehicle" => $vehicle, "advertisement" => $advertisement]);
    }
    
    public function detail(Vehicle $vehicle){
        return view('webview.detail', ["vehicle" => $vehicle]);
    }
}
