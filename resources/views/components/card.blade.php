@props(['href', 'vehicle_item'])

<a href="{{ $href }}" class="text-decoration-none d-block h-100">
    <div class="card h-100 pb-2">
        <img src="{{ $vehicle_item->main_image }}" class="card-img-top" alt="{{ $vehicle_item->vehicleName->name }}">

        <div class="card-body d-flex flex-column p-2">
            <h5 class="vehicle-card-title fw-semibold">
                {{ $vehicle_item->vehicleName->name }}
            </h5>

            <div class="d-flex align-items-baseline mt-1">
                <p class="vehicle-card-price text-primary fw-bold mb-2">
                    {{ 'Rp ' . number_format($vehicle_item->price, 0, ',', '.') }}
                </p>
                {{-- Menerjemahkan "/hari" --}}
                <p class="text-muted ms-1 mb-2" style="font-size: 0.8em;">@lang('app.card.per_day')</p>
            </div>

            {{-- Kategori dan detail lainnya diasumsikan sudah diterjemahkan dari Controller --}}
            <div class="vehicle-card-details text-muted">
                {{-- <p class="mb-2">
                    @foreach($vehicle_item->vehicleCategories as $kategori)
                        {{ $kategori->category }}{{ !$loop->last ? ', ' : '' }}
                    @endforeach
                </p> --}}
                <p class="m-0">
                    {{ $vehicle_item->year }}
                </p>
            </div>

            <div class="mt-auto pt-2">
                <div class="d-flex align-items-center justify-content-between vehicle-card-specs">
                    <span class="fw-bold">{{ $vehicle_item->vehicleTransmission->transmission }}</span>
                    <span class="badge text-bg-light border fw-semibold">{{ $vehicle_item->engine_cc . ' CC' }}</span>
                </div>
            </div>
        </div>
    </div>
</a>

<style>
    .vehicle-card-title {
        /* Kombinasi ajaib untuk memotong teks dengan "..." */
        white-space: nowrap;      /* 1. Mencegah teks turun ke baris baru */
        overflow: hidden;         /* 2. Sembunyikan teks yang berlebih */
        text-overflow: ellipsis;  /* 3. Tampilkan "..." */

        /* Font dibuat sedikit lebih kecil dari sebelumnya */
        font-size: clamp(0.85rem, 3.5vw, 1rem);
        line-height: 1.3;
        margin-bottom: 0.1rem;
    }

    .vehicle-card-price {
        /* Font harga juga dikecilkan */
        font-size: clamp(1rem, 4.5vw, 1.15rem);
        line-height: 1;
        margin-bottom: 0.25rem;
    }

    .vehicle-card-details, .vehicle-card-specs {
        /* Font untuk semua detail lainnya dikecilkan */
        font-size: clamp(0.7rem, 2.5vw, 0.8rem);
        line-height: 1.3;
    }

    .card {
        /* Menghapus bayangan dan menggunakan border solid */
        box-shadow: none !important;
        border: 1px solid #dee2e6; /* Warna border Bootstrap standar */
    }

    .card:hover {
        /* Efek hover yang lebih modern */
        border-color: #0d6efd; /* Warna biru primary Bootstrap */
        transition: border-color 0.2s ease-in-out;
    }
</style>
