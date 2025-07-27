<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    /**
     * Mengambil kategori mobil dari file bahasa.
     */
    private function getCarCategories(): array
    {
        return (array) __('vehicles.categories.car');
    }

    /**
     * Mengambil kategori motor dari file bahasa.
     */
    private function getMotorcycleCategories(): array
    {
        return (array) __('vehicles.categories.motorcycle');
    }

    /**
     * Method filter yang sudah diperbaiki dan dirapikan.
     */
    public function filter(Request $request, Builder $query)
    {
        // Validasi
        $validatedData = $request->validate([
            'Tipe_Kendaraan'  => 'nullable|string|in:Car,Motor',
            'Jenis_Kendaraan' => 'nullable|array',
            'Jenis_Transmisi' => 'nullable|array',
            'Tempat'          => 'nullable|array',
            'min_price'       => 'nullable|numeric|min:0',
            'max_price'       => 'nullable|numeric|gte:min_price',
            'start_date'      => 'nullable|date',
            'end_date'        => 'nullable|date|after_or_equal:start_date',
        ]);

        // Filter ketersediaan berdasarkan tanggal
        $startDate = $validatedData['start_date'] ?? null;
        $endDate = $validatedData['end_date'] ?? null;

        if ($startDate && $endDate) {
            $userStartDate = \Carbon\Carbon::parse($startDate);
            $userEndDate = \Carbon\Carbon::parse($endDate);
            $bufferStartDate = $userStartDate->copy()->subDay();
            $bufferEndDate = $userEndDate->copy()->addDay();

            $query->whereHas('transactions', function ($subQuery) use ($bufferStartDate, $bufferEndDate) {
                $subQuery
                    ->whereIn('transaction_status_id', [1, 2, 3])
                    ->where(function ($dateQuery) use ($bufferStartDate, $bufferEndDate) {
                        $dateQuery->where('start_book_date', '<=', $bufferEndDate)
                                  ->where('end_book_date', '>=', $bufferStartDate);
                    });
            });
        }

        // Terapkan filter lainnya
        $query
            ->when($request->filled('min_price'), function ($q) use ($request) {
                $q->where('price', '>=', $request->input('min_price'));
            })
            ->when($request->filled('max_price'), function ($q) use ($request) {
                $q->where('price', '<=', $request->input('max_price'));
            })
            ->when($request->filled('Tipe_Kendaraan'), function ($q) use ($request) {
                $q->whereHas('vehicleType', function ($subQuery) use ($request) {
                    $subQuery->where('type', $request->input('Tipe_Kendaraan'));
                });
            })
            ->when($request->filled('Jenis_Kendaraan'), function ($q) use ($request) {
                $q->whereHas('vehicleCategories', function ($subQuery) use ($request) {
                    $subQuery->whereIn('category', $request->input('Jenis_Kendaraan'));
                });
            })
            ->when($request->filled('Jenis_Transmisi'), function ($q) use ($request) {
                $q->whereHas('vehicleTransmission', function ($subQuery) use ($request) {
                    $subQuery->whereIn('transmission', $request->input('Jenis_Transmisi'));
                });
            })
            ->when($request->filled('Tempat'), function ($q) use ($request) {
                $q->whereIn('vehicle_location', $request->input('Tempat'));
            });

        return $query;
    }

    public function display(Request $request)
    {
        $vehicleQuery = Vehicle::query();
        $this->filter($request, $vehicleQuery);
        $vehicle = $vehicleQuery->latest()->paginate(16)->withQueryString();
        $advertisement = Advertisement::orderBy('id')->where('isactive', true)->get();

        return view('webview.homescreen', [
            "vehicle" => $vehicle,
            "advertisement" => $advertisement,
            "carCategories" => $this->getCarCategories(),
            "motorcycleCategories" => $this->getMotorcycleCategories(),
        ]);
    }

    public function catalog(Request $request)
    {
        $vehicleQuery = Vehicle::query();

        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            // ===================================================================
            // FIX: Berdasarkan struktur database Anda, merek dan nama model
            // ada di dalam tabel `vehicle_names`. Jadi, kita hanya perlu
            // mencari di dalam relasi `vehicleName`.
            // ===================================================================
            $vehicleQuery->whereHas('vehicleName', function ($subQuery) use ($searchTerm) {
                $subQuery->where('name', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        $this->filter($request, $vehicleQuery);

        $vehicle = $vehicleQuery->latest()->paginate(16)->withQueryString();

        return view('webview.catalog', [
            "vehicle" => $vehicle,
            "carCategories" => $this->getCarCategories(),
            "motorcycleCategories" => $this->getMotorcycleCategories(),
        ]);
    }

    public function detail(Vehicle $vehicle)
    {
        return view('webview.detail', ["vehicle" => $vehicle]);
    }
}
