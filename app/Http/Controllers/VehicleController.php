<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function filter(Request $request, Builder $query) // Pastikan $query tidak boleh null
    {
        if (!$request->filled('min_price')) {
            $request->merge(['min_price' => 0]);
        }
        
        $validatedData = $request->validate([
            'Tipe_Kendaraan'  => 'nullable|string|in:Mobil,Motor',
            'Jenis_Kendaraan' => 'nullable|array', // Seharusnya array karena dari checkbox
            'Jenis_Transmisi' => 'nullable|array', // Seharusnya array
            'Tempat'          => 'nullable|array', // Seharusnya array
            'min_price'       => 'required|numeric|gte:0',
            'max_price'       => 'nullable|numeric|gte:min_price'
        ]);

        // Gunakan $request->whenFilled() agar lebih bersih
        $query
            ->when($request->filled('Tipe_Kendaraan'), function ($q) use ($request) {
                return $q->where('type', $request->input('Tipe_Kendaraan'));
            })
            ->when($request->filled('Jenis_Kendaraan'), function ($q) use ($request) {
                return $q->whereIn('vehicle_category', $request->input('Jenis_Kendaraan'));
            })
            ->when($request->filled('Jenis_Transmisi'), function ($q) use ($request) {
                return $q->whereIn('transmission_type', $request->input('Jenis_Transmisi'));
            })
            ->when($request->filled('Tempat'), function ($q) use ($request) {
                return $q->whereIn('vehicle_location', $request->input('Tempat'));
            })
            ->when(isset($validatedData['min_price']), function ($q) use ($validatedData) {
                 return $q->where('price', '>=', $validatedData['min_price']);
            })
            ->when(isset($validatedData['max_price']), function ($q) use ($validatedData) {
                 return $q->where('price', '<=', $validatedData['max_price']);
            });
        
        return $query;
    }

    public function display(Request $request)
    {
        $vehicleQuery = Vehicle::query();

        $this->filter($request, $vehicleQuery);

        $vehicle = $vehicleQuery->orderBy('id')->paginate(12)->withQueryString();

        $advertisement = Advertisement::orderBy('id')->where('isactive', true)->get();

        return view('webview.homescreen', ["vehicle" => $vehicle, "advertisement" => $advertisement]);
    }

    public function catalog(Request $request)
    {
        $vehicleQuery = Vehicle::query();

        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $vehicleQuery->where('name', 'LIKE', '%' . $searchTerm . '%');
        }
        
        $this->filter($request, $vehicleQuery); 
        
        $vehicle = $vehicleQuery->orderBy('id')->paginate(12)->withQueryString();

        return view('webview.catalog', [
            "vehicle" => $vehicle
        ]); 
    }
    
    public function detail(Vehicle $vehicle){
        return view('webview.detail', ["vehicle" => $vehicle]);
    }
}
