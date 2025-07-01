<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function display(Request $request)
    {
        if (!$request->filled('min_price')) {
            $request->merge(['min_price' => 0]);
        }
        
        $validatedData = $request->validate([
            'Tipe_Kendaraan'    => 'nullable|string|in:Mobil,Motor', // Contoh validasi
            'Jenis_Kendaraan'   => 'nullable',
            'Jenis_Transmisi'   => 'nullable',
            'Tempat'            => 'nullable',
            'min_price'         => 'required|numeric|gte:0', // 'required' karena sudah kita pastikan ada di atas
            'max_price'         => 'nullable|numeric|gte:min_price'
        ]);

        $query = Vehicle::query()
            // Gunakan `when` untuk kode yang lebih bersih daripada `if`.
            // Kondisi hanya berjalan jika nilai ada di dalam $validatedData.

            ->when(isset($validatedData['Tipe_Kendaraan']), function ($q) use ($validatedData) {
                return $q->where('type', $validatedData['Tipe_Kendaraan']);
            })
            ->when(isset($validatedData['Jenis_Kendaraan']), function ($q) use ($validatedData) {
                return $q->whereIn('vehicle_category', $validatedData['Jenis_Kendaraan']);
            })
            ->when(isset($validatedData['Jenis_Transmisi']), function ($q) use ($validatedData) {
                return $q->whereIn('transmission_type', $validatedData['Jenis_Transmisi']);
            })
            ->when(isset($validatedData['Tempat']), function ($q) use ($validatedData) {
                return $q->whereIn('vehicle_location', $validatedData['Tempat']);
            })
            ->when(isset($validatedData['min_price']), function ($q) use ($validatedData) {
                // Sekarang kita menggunakan data yang sudah divalidasi dan di-default
                return $q->where('price', '>=', $validatedData['min_price']);
            })
            ->when(isset($validatedData['max_price']), function ($q) use ($validatedData) {
                return $q->where('price', '<=', $validatedData['max_price']);
            });


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
