@props(['href','vehicle_item'])

<script>
    // Pilih elemen berdasarkan ID yang tadi dibuat
    const element = document.getElementById('dynamicHeightElement');

    // Tentukan tinggi dasar yang Anda inginkan dalam satuan vh
    const baseHeightVH = 20;

    function adjustHeightForZoom() {
        // window.devicePixelRatio berubah sesuai dengan tingkat zoom browser.
        // Pada zoom 100%, nilainya 1. Pada zoom 200%, nilainya 2. Pada zoom 50%, nilainya 0.5.
        const zoomRatio = window.devicePixelRatio || 1;

        // Logika perhitungannya:
        // Untuk menjaga ukuran visual, kita harus membagi tinggi dasar dengan rasio zoom.
        // Contoh: Jika zoom 50% (ratio=0.5), tinggi baru = 20 / 0.5 = 40vh.
        const newHeight = baseHeightVH / zoomRatio;

        // Terapkan tinggi baru ke style elemen
        element.style.height = newHeight + 'vh';
        
        // (Opsional) Tampilkan di console untuk debugging
        console.log(`Zoom Ratio: ${zoomRatio}, New Height: ${newHeight}vh`);
    }

    // Jalankan fungsi saat halaman pertama kali dimuat
    document.addEventListener('DOMContentLoaded', adjustHeightForZoom);

    // Jalankan fungsi setiap kali jendela browser diubah ukurannya (zoom juga memicu event ini)
    window.addEventListener('resize', adjustHeightForZoom);
</script>

<a href="{{ $href }}" class="text-decoration-none d-block">
    <div class="mt-2 m-1 border border-dark rounded-3">
        <span>
            <img src="{{$vehicle_item->image}}" alt="" class = "rounded-top" style="width: 100%; aspect-ratio: 3 / 2; ">
        </span>
    
        {{-- <hr class = "p-0 mt-2 mb-1"> --}}
    
        <div class="p-2">
            <div class="m-0 ps-1" id="dynamicHeightElement" style="overflow:hidden;">
                <p class="text-dark fw-semibold mb-0 m-0"> {{ $vehicle_item->name }} </p>
                <div class = "d-flex align-items-end m-0">
                    <p class="text-primary fw-bold fs-4 m-0"> {{ "Rp. $vehicle_item->price" }} </p>
                    <p class="text-secondary mb-1">/day</p>
                </div>
                <div class = "d-flex align-items-center justify-content-between mt-2 mb-1">
                    <p class = "text-dark fw-bold mt-0 mb-0 fs-6"> {{ $vehicle_item->year }} </p>
                    <div class="rounded-4 border border-dark ps-3 pe-3 ms-2">
                        <p class = "text-dark fw-semibold m-0"> {{ "$vehicle_item->engine_cc CC" }} </p>
                    </div>
                </div>
                <p class = "text-dark m-0 p-0 mb-3"> {{ $vehicle_item->transmission_type }} </p>
            </div>
        </div>
    </div>
</a>