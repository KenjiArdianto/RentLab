<?php

namespace App\Http\Controllers;

use App\Http\Requests\VehicleFilterRequest; // <-- 1. Gunakan Form Request ini
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

    private function filter(VehicleFilterRequest $request, Builder $query)
    {
        $filters = $request->validated();

        // Filter ketersediaan berdasarkan tanggal
        if (isset($filters['start_date']) && isset($filters['end_date'])) {
            $userStartDate = \Carbon\Carbon::parse($filters['start_date']);
            $userEndDate = \Carbon\Carbon::parse($filters['end_date']);
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
            ->when($filters['min_price'] ?? null, function ($q, $minPrice) {
                $q->where('price', '>=', $minPrice);
            })
            ->when($filters['max_price'] ?? null, function ($q, $maxPrice) {
                $q->where('price', '<=', $maxPrice);
            })
            ->when($filters['Tipe_Kendaraan'] ?? null, function ($q, $type) {
                $q->whereHas('vehicleType', function ($subQuery) use ($type) {
                    $subQuery->where('type', $type);
                });
            })
            ->when($filters['Jenis_Kendaraan'] ?? null, function ($q, $categories) {
                $q->whereHas('vehicleCategories', function ($subQuery) use ($categories) {
                    $subQuery->whereIn('category', $categories);
                });
            })
            ->when($filters['Jenis_Transmisi'] ?? null, function ($q, $transmissions) {
                $q->whereHas('vehicleTransmission', function ($subQuery) use ($transmissions) {
                    $subQuery->whereIn('transmission', $transmissions);
                });
            })
            ->when($filters['Tempat'] ?? null, function ($q, $locations) {
                $q->whereHas('location', function ($subQuery) use ($locations) {
                    $subQuery->whereIn('name', $locations);
                });
            });

        return $query;
    }

    /**
     * Menampilkan kendaraan di halaman utama dengan filter.
     */
    public function display(VehicleFilterRequest $request) // <-- 2. Ganti Request biasa dengan Form Request
    {
        $vehicleQuery = Vehicle::query();
        $this->filter($request, $vehicleQuery); // Panggil private method filter
        $vehicle = $vehicleQuery->latest()->paginate(16)->withQueryString();

        $advertisement = Advertisement::orderBy('id')->where('isactive', true)->get();

        return view('webview.homescreen', [
            "vehicle" => $vehicle,
            "advertisement" => $advertisement,
            "carCategories" => $this->getCarCategories(),
            "motorcycleCategories" => $this->getMotorcycleCategories(),
        ]);
    }

    public function catalog(VehicleFilterRequest $request) // <-- 2. Ganti Request biasa dengan Form Request
    {
        $vehicleQuery = Vehicle::query();
        $filters = $request->validated(); // Ambil data yang sudah bersih

        // Logika pencarian khusus untuk katalog
        if (isset($filters['search'])) {
            $vehicleQuery->whereHas('vehicleName', function ($subQuery) use ($filters) {
                $subQuery->where('name', 'LIKE', '%' . $filters['search'] . '%');
            });
        }

        $this->filter($request, $vehicleQuery); // Panggil private method filter
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
