@props(['href','vehicle_item'])

<a href="{{ $href }}" class="text-decoration-none d-block">
    <div class="mt-2 m-1 border border-dark rounded-3" style="width: 100%; aspect-ratio: 3 / 1.75; ">
        <span>
            <img src="{{$vehicle_item->main_image}}" alt="" class = "rounded-top" style="width: 100%; aspect-ratio: 3 / 1.75; ">
        </span>

        <div class="p-2">
            <div class="m-0 ps-1" id="dynamicHeightElement" style="overflow:hidden;">
                <p class="text-dark fw-semibold mb-0 m-0"> {{ $vehicle_item->vehicleName->name }} </p>
                <div class = "d-flex align-items-end m-0">
                    {{-- Tambahkan kelas no-text-zoom di sini --}}
                    <p class="text-primary fw-bold fs-4 m-0 no-text-zoom"> {{ "Rp. $vehicle_item->price" }} </p>
                    <p class="text-secondary mb-1 no-text-zoom">/day</p> {{-- Juga tambahkan di sini --}}
                </div>
                @foreach($vehicle_item->vehicleCategories as $kategori)
                    {{-- Tampilkan nama kategori, tambahkan koma jika bukan item terakhir --}}
                    <span class="no-text-zoom">{{ $kategori->category }}{{ !$loop->last ? ', ' : '' }}</span>
                @endforeach
                <div class = "d-flex align-items-center justify-content-between mt-2 mb-1">
                    <p class = "text-dark fw-bold mt-0 mb-0 fs-6 no-text-zoom"> {{ $vehicle_item->year }} </p>
                    <div class="rounded-4 border border-dark ps-3 pe-3 ms-2">
                        <p class = "text-dark fw-semibold m-0 no-text-zoom"> {{ "$vehicle_item->engine_cc CC" }} </p>
                    </div>
                </div>
                <p class = "text-dark m-0 p-0 mb-3 no-text-zoom"> {{ $vehicle_item->vehicleTransmission->transmission }} </p>
            </div>
        </div>
    </div>
</a>
