<x-layout>
    <div class="container-fluid px-0">
        <div id="carouselExampleIndicators" class="carousel slide w-100" style="width: 100%;">
                <div class="carousel-indicators">
                    @foreach ($advertisement as $adv)
                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="{{ $loop->index }}" class="@if($loop->first) active @endif" aria-current="true" aria-label="Slide {{ $loop->iteration }}"></button>
                    @endforeach
                </div>
                <div class="carousel-inner">
                    @foreach ($advertisement as $adv)
                        <div class="carousel-item @if($loop->first) active @endif">
                            <img src="{{asset($adv->path)}}" class="d-block w-100" alt="...">
                        </div>
                    @endforeach
                </div>
                <button class="carousel-control-prev" type="button"  data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
        </div>
    </div>
     <div class="container-fluid mt-4">
        <div class="row p-1">
        {{-- Kolom 1: SIDEBAR FILTER (Sekarang menjadi Offcanvas Responsif) --}}
        {{-- Penjelasan Kelas Baru:
            - offcanvas-lg: Berfungsi sebagai offcanvas DI BAWAH breakpoint 'lg' (992px).
            - offcanvas-start: Muncul dari sisi kiri saat menjadi offcanvas.
            - col-lg-3: Di layar 'lg' ke atas, sidebar akan mengambil 3 dari 12 kolom grid.
            - Hapus style="width: 22vw;" karena sudah diatur oleh col-lg-3.
        --}}

        <div class="col-xl-3 bg-light p-3 offcanvas-xl offcanvas-start" tabindex="-1" id="filterSidebar" aria-labelledby="filterSidebarLabel">

            {{-- Header untuk Offcanvas, hanya terlihat di mobile (d-lg-none) --}}
            <div class="offcanvas-header d-xl-none">
                <h5 class="offcanvas-title" id="filterSidebarLabel">Filter Kendaraan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#filterSidebar" aria-label="Close"></button>
            </div>

            {{-- Body Offcanvas, berisi form filter Anda --}}
            <div class="offcanvas-body">
                {{-- Form Anda ditempatkan di sini tanpa perubahan isi --}}
                <form action="{{ route('vehicle.display') }}" method="GET" id="filterForm">
                    {{-- ... SEMUA ISI FORM ANDA ADA DI SINI ... --}}
                    {{-- (mulai dari @csrf sampai tombol Filter/Reset) --}}

                    @php
                        $activeType = old('Tipe_Kendaraan', request('Tipe_Kendaraan', 'Car'));
                        $activeTransmissions = old('Jenis_Transmisi', request('Jenis_Transmisi', []));
                        $activeCategories = old('Jenis_Kendaraan', request('Jenis_Kendaraan', []));
                    @endphp

                    {{-- Judul ini bisa untuk desktop, karena di mobile sudah ada di header --}}
                    <h5 class="d-none d-lg-block text-center">Filter Kendaraan</h5>
                    <hr class="d-none d-lg-block m-2">

                    @if(request()->has('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif

                    {{-- ( ... sisa form Anda secara lengkap ... ) --}}
                    <div class="btn-group w-100 mb-3" role="group">
                        <input type="radio" class="btn-check" name="Tipe_Kendaraan" id="opsiMobil" value="Car" @if($activeType == 'Car') checked @endif>
                        <label class="btn btn-outline-primary" for="opsiMobil">Mobil</label>

                        <input type="radio" class="btn-check" name="Tipe_Kendaraan" id="opsiMotor" value="Motor" @if($activeType == 'Motor') checked @endif>
                        <label class="btn btn-outline-primary" for="opsiMotor">Motor</label>
                    </div>

                    <div id="kalender-inline" class="mb-3"></div>

                    <input type="hidden" name="start_date" id="start_date_hidden" value="{{ old('start_date', request('start_date')) }}">
                    <input type="hidden" name="end_date" id="end_date_hidden" value="{{ old('end_date', request('end_date')) }}">

                    <div id="wrapper-kategori-mobil">
                        @foreach($carCategories as $jenis)
                            @php $id = 'check-mobil-' . Str::slug($jenis); @endphp
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="Jenis_Kendaraan[]" value="{{ $jenis }}" id="{{ $id }}" @if(in_array($jenis, $activeCategories)) checked @endif>
                                <label class="form-check-label" for="{{ $id }}">{{ $jenis }}</label>
                            </div>
                        @endforeach
                    </div>

                    <div id="wrapper-kategori-motor" style="display: none;">
                        @foreach($motorcycleCategories as $jenis)
                            @php $id = 'check-motor-' . Str::slug($jenis); @endphp
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="Jenis_Kendaraan[]" id="{{ $id }}" value="{{ $jenis }}" @if(in_array($jenis, $activeCategories)) checked @endif>
                                <label class="form-check-label" for="{{ $id }}">{{ $jenis }}</label>
                            </div>
                        @endforeach
                    </div>

                    <hr>

                    <div class="row mb-3">
                        <div id="wrapper-manual" class="col-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="Jenis_Transmisi[]" id="checkManual" value="Manual" @if(in_array('Manual', $activeTransmissions)) checked @endif>
                                <label class="form-check-label" for="checkManual">Manual</label>
                            </div>
                        </div>
                        <div id="wrapper-matic" class="col-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="Jenis_Transmisi[]" id="checkAutomatic" value="Automatic" @if(in_array('Automatic', $activeTransmissions)) checked @endif>
                                <label class="form-check-label" for="checkAutomatic">Automatic</label>
                            </div>
                        </div>
                        <div id="wrapper-kopling" class="col-4" style="display: none;">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="Jenis_Transmisi[]" id="checkKopling" value="Kopling" @if(in_array('Kopling', $activeTransmissions)) checked @endif>
                                <label class="form-check-label" for="checkKopling">Kopling</label>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <p class="p-2 pb-1 mb-2 fs-5">Jangkauan Harga</p>

                    <div class="ps-2 pe-2 d-flex pb-1 mb-1 align-items-center justify-content-center">
                        <input type="text" class="form-control @error('min_price') is-invalid @enderror" name="min_price" id="min_price" placeholder="Min" value="{{ old('min_price', request('min_price', 0)) }}">
                        <img src="{{ asset('page_assets/arrow.png') }}" alt="->" class="m-2" height="20px">
                        <input type="text" class="form-control @error('max_price') is-invalid @enderror" name="max_price" id="max_price" placeholder="Max" value="{{ old('max_price', request('max_price')) }}">
                    </div>

                    <div class="ps-2 pe-2 mb-3">
                        @error('min_price') <div class="text-danger small">{{ $message }}</div> @enderror
                        @error('max_price') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>

                    <div class="container-fluid d-flex p-0">
                        <a href="{{ route('vehicle.display') }}" class="container-fluid btn btn-secondary m-2">Reset</a>
                        <button type="submit" class="container-fluid btn btn-primary m-2">Filter</button>
                    </div>
                    {{-- ... akhir dari form ... --}}
                </form>
            </div>
        </div>

        {{-- Kolom 2: KONTEN UTAMA --}}
        {{-- Di layar 'lg' ke atas, konten akan mengambil 9 dari 12 kolom grid. --}}
        {{-- Di bawah 'lg', kolom ini akan otomatis mengambil lebar penuh (full width). --}}
        <div class="col-xl-9">
            {{-- TOMBOL BARU UNTUK MEMBUKA FILTER DI MOBILE --}}
            <div class="d-grid mb-3 d-xl-none">
                <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#filterSidebar" aria-controls="filterSidebar">
                    <i class="bi bi-filter"></i> Tampilkan Filter
                </button>
            </div>

            <div class="row g-4">
                @forelse ($vehicle as $vehicle_item)
                    <div class="col-6 col-md-4 col-lg-3">
                        <x-card href="{{ route('vehicle.detail', $vehicle_item->id) }}" :vehicle_item="$vehicle_item" />
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-warning text-center">
                            Tidak ada kendaraan yang sesuai dengan filter Anda.
                        </div>
                    </div>
                @endforelse
            </div>
            {{-- Pagination Anda --}}
            <div class="mt-4">
                <p class="text-center text-muted small">
                    Showing {{ $vehicle->firstItem() }} to {{ $vehicle->lastItem() }} of {{ $vehicle->total() }} results
                </p>
                <div class="d-flex justify-content-center">
                    {{ $vehicle->links() }}
                </div>
            </div>
        </div>

    </div>

    </div>

    @push('scripts')
    <script>
        /**
         * FUNGSI BARU: Mereset SEMUA filter (checkbox, harga, dan tanggal).
         * Menerima instance Flatpickr agar bisa mereset kalender.
         */
        function resetAllFilters(flatpickrInstance) {
            // Reset semua checkbox
            document.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);

            // Reset input harga ke kondisi default
            const minPriceInput = document.getElementById('min_price');
            const maxPriceInput = document.getElementById('max_price');
            if (minPriceInput) minPriceInput.value = '0'; // Kembalikan ke default
            if (maxPriceInput) maxPriceInput.value = '';  // Kosongkan

            // Reset input tanggal yang tersembunyi
            const startDateInput = document.getElementById('start_date_hidden');
            const endDateInput = document.getElementById('end_date_hidden');
            if(startDateInput) startDateInput.value = '';
            if(endDateInput) endDateInput.value = '';

            // Reset tampilan kalender Flatpickr jika ada
            if (flatpickrInstance) {
                flatpickrInstance.clear();
            }
        }

        /**
         * Mengatur tampilan form (show/hide) berdasarkan tipe kendaraan.
         * (Fungsi ini tidak berubah)
         */
        function tampilkanForm(tipe) {
            const wrapperManual = document.getElementById('wrapper-manual');
            const wrapperMatic = document.getElementById('wrapper-matic');
            const wrapperKopling = document.getElementById('wrapper-kopling');
            const wrapperKategoriMobil = document.getElementById('wrapper-kategori-mobil');
            const wrapperKategoriMotor = document.getElementById('wrapper-kategori-motor');

            if (tipe === 'Car') {
                wrapperKopling.style.display = 'none';
                wrapperManual.className = 'col-6';
                wrapperMatic.className = 'col-6';
                wrapperKategoriMobil.style.display = 'block';
                wrapperKategoriMotor.style.display = 'none';
            } else if (tipe === 'Motor') {
                wrapperKopling.style.display = 'block';
                wrapperManual.className = 'col-4';
                wrapperMatic.className = 'col-4';
                wrapperKopling.className = 'col-4';
                wrapperKategoriMobil.style.display = 'none';
                wrapperKategoriMotor.style.display = 'block';
            }
        }

        // --- EVENT LISTENER UTAMA ---
        document.addEventListener('DOMContentLoaded', function () {
            const filterForm = document.getElementById('filterForm');
            if (!filterForm) return;

            // Ambil elemen yang kita butuhkan
            const radios = filterForm.querySelectorAll('input[name="Tipe_Kendaraan"]');
            const startDateInput = document.getElementById('start_date_hidden');
            const endDateInput = document.getElementById('end_date_hidden');

            // Deklarasikan variabel instance di sini agar bisa diakses oleh semua
            let calendarInstance = null;

            // Inisialisasi Tampilan Awal
            const tipeAktifSaatIni = '{{ request("Tipe_Kendaraan", "Car") }}';
            tampilkanForm(tipeAktifSaatIni);

            // Inisialisasi Flatpickr
            if (document.getElementById('kalender-inline')) {
                // Cek apakah input tanggal ada dan memiliki nilai
                let tanggalDefault = [];
                if (startDateInput && endDateInput && startDateInput.value && endDateInput.value) {
                    tanggalDefault = [startDateInput.value, endDateInput.value];
                }

                // Simpan instance ke variabel yang sudah kita deklarasikan
                calendarInstance = flatpickr("#kalender-inline", {
                    inline: true,
                    mode: "range",
                    dateFormat: "Y-m-d",
                    minDate: "today",
                    locale: "id",
                    defaultDate: tanggalDefault,
                    onChange: function(selectedDates) {
                        // Hanya proses jika pengguna sudah memilih rentang tanggal yang lengkap
                        if (selectedDates.length === 2) {
                            const formatDate = (date) => {
                                const year = date.getFullYear();
                                const month = String(date.getMonth() + 1).padStart(2, '0');
                                const day = String(date.getDate()).padStart(2, '0');
                                return `${year}-${month}-${day}`;
                            };
                            startDateInput.value = formatDate(selectedDates[0]);
                            endDateInput.value = formatDate(selectedDates[1]);
                        } else {
                            startDateInput.value = '';
                            endDateInput.value = '';
                        }
                    }
                });
            }

            // Pasang Event Listener HANYA untuk Radio Button (SATU KALI)
            radios.forEach(radio => {
                radio.addEventListener('change', function() {
                    // Saat pindah tipe, reset semuanya termasuk kalender
                    resetAllFilters(calendarInstance);
                    tampilkanForm(this.value);
                    filterForm.submit();
                });
            });
        });
    </script>
    @endpush
</x-layout>
