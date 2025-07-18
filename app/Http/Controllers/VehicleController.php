<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    private function getCarCategories(): array
    {
        return [
            'Sedan', 'SUV', 'Hatchback', 'Convertible', 'Coupe', 'Wagon',
            'Pickup', 'Van', 'Electric', 'Hybrid', 'Luxury', 'Off-Road',
            'Sport', 'City', 'Custom'
        ];
    }

    private function getMotorcycleCategories(): array
    {
        return ['Scooter', 'Moped', 'Sport', 'Cruiser', 'Touring', 'Off-Road', 'Naked', 'Electric', 'Commuter', 'Custom'];
    }

    /**
     * Method filter yang sudah diperbaiki dan dirapikan.
     */
    public function filter(Request $request, Builder $query)
    {
        // FIX 1: Validasi sekarang menggunakan nama yang benar: 'start_date' & 'end_date'
        $validatedData = $request->validate([
            'Tipe_Kendaraan'  => 'nullable|string|in:Car,Motor',
            'Jenis_Kendaraan' => 'nullable|array',
            'Jenis_Transmisi' => 'nullable|array',
            'Tempat'          => 'nullable|array',
            'min_price'       => 'nullable|numeric|gte:0',
            'max_price'       => 'nullable|numeric|gte:min_price',
            'start_date'      => 'nullable|date',
            'end_date'        => 'nullable|date|after_or_equal:start_date',
        ]);

        // FIX 2: Ambil data tanggal dari request dengan nama yang benar
        // Ambil tanggal dari data yang sudah divalidasi
        $startDate = $validatedData['start_date'] ?? null;
        $endDate = $validatedData['end_date'] ?? null;

        // Logika filter ketersediaan dengan BUFFER 1 HARI
        if ($startDate && $endDate) {

            // 1. Ubah string tanggal menjadi objek Carbon
            $userStartDate = \Carbon\Carbon::parse($startDate);
            $userEndDate = \Carbon\Carbon::parse($endDate);

            // 2. Buat rentang buffer: H-1 dan H+1
            $bufferStartDate = $userStartDate->copy()->subDay();
            $bufferEndDate = $userEndDate->copy()->addDay();

            // 3. Jalankan query dengan rentang buffer
            $query->whereHas('transactions', function ($subQuery) use ($bufferStartDate, $bufferEndDate) {
                $subQuery
                    // Hanya cari transaksi yang aktif (status 1, 2, atau 3)
                    ->whereIn('status', [1, 2, 3])

                    // Cek tumpang tindih dengan RENTANG BUFFER
                    ->where(function ($dateQuery) use ($bufferStartDate, $bufferEndDate) {
                        $dateQuery->where('start_book_date', '<=', $bufferEndDate)
                                ->where('end_book_date', '>=', $bufferStartDate);
                    });
            });
        }

        // Langkah 3: Terapkan semua filter lain menggunakan 'when' dan 'filled()'
        $query
            // Selalu terapkan harga minimum, default ke 0 jika kosong.
            ->where('price', '>=', $request->input('min_price', 0))

            // Terapkan filter lain HANYA JIKA diisi oleh pengguna
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
        if (!$request->has('Tipe_Kendaraan')) {
            $request->merge(['Tipe_Kendaraan' => 'Car']);
        }

        $vehicleQuery = Vehicle::query();

        $this->filter($request, $vehicleQuery);
        // dd($vehicleQuery->toSql());

        $vehicle = $vehicleQuery->orderBy('id')->paginate(16)->withQueryString();
        $advertisement = Advertisement::orderBy('id')->where('isactive', true)->get();

        return view(
            'webview.homescreen',
            [
                "vehicle" => $vehicle,
                "advertisement" => $advertisement,
                // FIX 3: Ambil daftar kategori dari fungsi terpusat
                "carCategories" => $this->getCarCategories(),
                "motorcycleCategories" => $this->getMotorcycleCategories(),
            ]
        );
    }

    public function catalog(Request $request)
    {
        if (!$request->has('Tipe_Kendaraan')) {
            $request->merge(['Tipe_Kendaraan' => 'Car']);
        }
        // ============================= FIX ENDS HERE =============================

        $vehicleQuery = Vehicle::query();

        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $vehicleQuery->whereHas('vehicleName', function ($q) use ($searchTerm) {
                $q->where('name', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        // Sekarang, saat filter() dipanggil, Tipe_Kendaraan sudah ada di dalam request,
        // sehingga filter tipe kendaraan akan diterapkan dengan benar.
        $this->filter($request, $vehicleQuery);

        $vehicle = $vehicleQuery->orderBy('id')->paginate(16)->withQueryString();

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
