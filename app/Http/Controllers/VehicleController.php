<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\UserReview;
use App\Models\Vehicle;
use App\Models\VehicleImage;
use App\Models\Advertisement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\VehicleFilterRequest;
use App\Models\Location;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;



class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $listItem = Vehicle::all();
        // PERBAIKAN: Hanya catat log jika user sudah login
        if (Auth::check()) {
            activity('vehicle_index')
            ->causedBy(Auth::user())
            ->withProperties(['ip' => request()->ip()])
            ->log("Viewed vehicle page");
        }
        return view('welcome', compact('listItem'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $idVehicle = Vehicle::with([
            'vehicleCategories',
            'vehicleName',
            'vehicleType',
            'vehicleTransmission',
            'location'
        ])->findOrFail($id);

        //get vehicle id
        if(Auth::check()){
            $getVehicleByIdsINCarts = Cart::where('user_id', Auth::user()->id)->where('vehicle_id', $id)->get();

            $cartDateRanges = $getVehicleByIdsINCarts->map(function ($item) {
            return [
                'start_date' => $item->start_date,
                'end_date' => $item->end_date,
            ];
        });
        }else{
            $getVehicleByIdsINCarts=null;
            $cartDateRanges=null;
        }
        $getCommentByIdVehicle = UserReview::whereHas('transaction', function ($query) use ($id) {
            $query->where('vehicle_id', $id);
        })->get();

        $getVehicleimagesById = VehicleImage::where("vehicle_id","=",$id)->get();


        // $getVehicleByIdsINCarts = Cart::where('user_id', auth()->id() )->where('vehicle_id', $id)->get();

        $getCommentByIdVehicle = UserReview::whereHas('transaction', function ($query) use ($id) {
            $query->where('vehicle_id', $id);
        })->get();

        $getVehicleimagesById = VehicleImage::where("vehicle_id","=",$id)->get();




        $rating = DB::table('user_reviews')
        ->join('transactions', 'user_reviews.transaction_id', '=', 'transactions.id')
        ->join('vehicles', 'transactions.vehicle_id', '=', 'vehicles.id')
        ->select(DB::raw('ROUND(AVG(user_reviews.rate), 1) as average_rating'))
        ->where('vehicles.id', $id)
        ->groupBy('vehicles.id')
        ->first();

        // PERBAIKAN: Hanya catat log jika user sudah login
        if (Auth::check()) {
            \activity('vehicle_show')
            ->causedBy(Auth::user())
            ->withProperties([
                'ip' => request()->ip(),
                'vehicle_id' => $id,
                'user_agent' => request()->userAgent(),
            ])
            ->log("Viewed vehicle detail");
        }


    $bookedTransactionDates = Transaction::where('vehicle_id', $id)
    ->where('transaction_status_id', '<', 7)
    ->select('start_book_date as start_date', 'end_book_date as end_date')
    ->get();

    $allBookedDates = $bookedTransactionDates;


    return view('DetailPage', compact(
            'rating',
            'idVehicle',
            'getVehicleByIdsINCarts',
            'getCommentByIdVehicle',
            'cartDateRanges',
            'getVehicleimagesById',
            'allBookedDates'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

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

        if (isset($filters['start_date']) && isset($filters['end_date'])) {
            $userStartDate = \Carbon\Carbon::parse($filters['start_date']);
            $userEndDate = \Carbon\Carbon::parse($filters['end_date']);
            $bufferStartDate = $userStartDate->copy()->subDay();
            $bufferEndDate = $userEndDate->copy()->addDay();

            $query->whereDoesntHave('transactions', function ($subQuery) use ($bufferStartDate, $bufferEndDate) {
                $subQuery
                    ->whereIn('transaction_status_id', [1, 2, 3])
                    ->where(function ($dateQuery) use ($bufferStartDate, $bufferEndDate) {
                        $dateQuery->where('start_book_date', '<=', $bufferEndDate)
                                  ->where('end_book_date', '>=', $bufferStartDate);
                    });
            });
        }

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
            ->when($filters['Tempat'] ?? null, function ($q, $location_ids) {
                $q->whereIn('location_id', $location_ids);
            });

        return $query;
    }

    /**
     * Menampilkan kendaraan di halaman utama dengan filter.
     */
    public function display(VehicleFilterRequest $request)
    {
        $vehicleQuery = Vehicle::query();

        $this->filter($request, $vehicleQuery);
        $vehicle = $vehicleQuery->latest()->paginate(16)->withQueryString();

        // Muat relasi SETELAH paginasi untuk memastikan data selalu ada
        $vehicle->load(['vehicleName', 'vehicleType', 'vehicleTransmission', 'location']);

        $advertisement = Advertisement::orderBy('id')->where('isactive', true)->get();
        $locations = Location::orderBy('location')->get();

        // PERBAIKAN: Hanya catat log jika user sudah login
        if (Auth::check()) {
            \activity('vehicle_display')
            ->causedBy(Auth::user())
            ->withProperties([
                'ip' => request()->ip(),
                'filter_applied'=>$request->validated(),
                'vehicle_shown_count' => $vehicle->total(),
                'user_agent' => request()->userAgent(),
            ])
            ->log('User filtered and viewed vehicle list');
        }

        return view('webview.homescreen', [
            "vehicle" => $vehicle,
            "advertisement" => $advertisement,
            "carCategories" => $this->getCarCategories(),
            "motorcycleCategories" => $this->getMotorcycleCategories(),
            "locations" => $locations,
        ]);
    }

    public function catalog(VehicleFilterRequest $request)
    {
        $vehicleQuery = Vehicle::query();

        $filters = $request->validated();

        if (isset($filters['search'])) {
            $vehicleQuery->whereHas('vehicleName', function ($subQuery) use ($filters) {
                $subQuery->where('name', 'LIKE', '%' . $filters['search'] . '%');
            });
        }

        $this->filter($request, $vehicleQuery);
        $vehicle = $vehicleQuery->latest()->paginate(16)->withQueryString();

        // Muat relasi SETELAH paginasi untuk memastikan data selalu ada
        $vehicle->load(['vehicleName', 'vehicleType', 'vehicleTransmission', 'location']);

        $locations = Location::orderBy('location')->get();

        // PERBAIKAN: Hanya catat log jika user sudah login
        if (Auth::check()) {
            \activity('vehicle_catalog')
            ->causedBy(Auth::user())
            ->withProperties([
                'ip' => request()->ip(),
                'filters' => $filters,
                'search' => $filters['search'] ?? null,
                'result_count' => $vehicle->total(),
                'user_agent' => request()->userAgent(),
            ])
            ->log('User searched and filtered vehicle catalog');
        }

        return view('webview.catalog', [
            "vehicle" => $vehicle,
            "carCategories" => $this->getCarCategories(),
            "motorcycleCategories" => $this->getMotorcycleCategories(),
            "locations" => $locations,
        ]);
    }


    public function detail(Vehicle $vehicle)
    {
        return view('webview.detail', ["vehicle" => $vehicle]);
    }
}
