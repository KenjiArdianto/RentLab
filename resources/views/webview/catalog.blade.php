<x-layout>
     <div class="container-fluid mt-4 pe-0">
        <div class="d-flex justify-content-center">

            {{-- Kolom 1: SIDEBAR FILTER --}}
            <div class="flex-shrink-0 p-3 bg-light" style="width: 22vw; overflow-y:visible; overflow-x:hidden;">
                <div>
                    {{-- PASTIKAN ACTION MENUJU KE ROUTE KATALOG --}}
                    <form action="{{ route('vehicle.catalog') }}" method="GET" id="filterForm">

                        @php
                            // Logika ini mengambil state filter dari URL, default ke 'Car'
                            $activeType = request('Tipe_Kendaraan', 'Car');
                            $activeTransmissions = request('Jenis_Transmisi', []);
                            $activeCategories = request('Jenis_Kendaraan', []);
                        @endphp

                        <h5 class="d-flex justify-content-center">Filter Kendaraan</h5>
                        <hr>

                        {{-- Jika ada pencarian, nilainya akan tetap dibawa saat memfilter --}}
                        @if(request()->has('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif

                        {{-- Filter Tipe Kendaraan (Mobil/Motor) --}}
                        <div class="btn-group w-100 mb-3" role="group">
                            <input type="radio" class="btn-check" name="Tipe_Kendaraan" id="opsiMobil" value="Car" @if($activeType == 'Car') checked @endif>
                            <label class="btn btn-outline-primary" for="opsiMobil">Mobil</label>

                            <input type="radio" class="btn-check" name="Tipe_Kendaraan" id="opsiMotor" value="Motor" @if($activeType == 'Motor') checked @endif>
                            <label class="btn btn-outline-primary" for="opsiMotor">Motor</label>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-center">
                                <div id="kalender-inline"></div>
                            </div>
                        </div>

                        <input type="hidden" name="start_date" id="start_date_hidden" value="{{ request('start_date') }}">
                        <input type="hidden" name="end_date" id="end_date_hidden" value="{{ request('end_date') }}">

                        <hr>

                        {{-- Filter Jenis Kendaraan (Dinamis) --}}
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

                        {{-- Filter Transmisi (Dinamis) --}}
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

                        {{-- Letakkan kode ini setelah blok filter transmisi --}}
                        <hr>

                        <p class="p-2 pb-1 mb-2 fs-5">Jangkauan Harga</p>

                        <div class="ps-2 pe-2 d-flex pb-1 mb-1 align-items-center justify-content-center">
                            <input
                                type="text"
                                class="form-control @error('min_price') is-invalid @enderror"
                                name="min_price"
                                id="min_price"
                                placeholder="Min"
                                value="{{ request('min_price', 0) }}">

                            <img src="{{ asset('page_assets/arrow.png') }}" alt="->" class="m-2" height="20px">

                            <input
                                type="text"
                                class="form-control @error('max_price') is-invalid @enderror"
                                name="max_price"
                                id="max_price"
                                placeholder="Max"
                                value="{{ request('max_price') }}">
                        </div>

                        <div class="ps-2 pe-2 mb-3">
                            @error('min_price') <div class="text-danger small">{{ $message }}</div> @enderror
                            @error('max_price') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="container-fluid d-flex p-0 mt-3">
                            <a href="{{ route('vehicle.catalog') }}" class="container-fluid btn btn-secondary m-2">Reset</a>
                            <button type="submit" class="container-fluid btn btn-primary m-2">Filter</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Kolom 2: KONTEN UTAMA --}}
            <div class="flex-grow-1 p-3">
                <div class="row g-4">
                    @forelse ($vehicle as $vehicle_item)
                        <div class="col-12 col-md-6 col-lg-4 col-xl-3 p-1">
                            <x-card href="{{ route('vehicle.detail', $vehicle_item->id) }}" :vehicle_item="$vehicle_item" />
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-warning text-center">
                                Tidak ada kendaraan yang sesuai dengan filter atau pencarian Anda.
                            </div>
                        </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                <div class="mt-4 d-flex justify-content-center">
                    {{ $vehicle->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPT YANG BENAR UNTUK LOGIKA FILTER INTERAKTIF --}}
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

                            // FUNGSI BARU yang aman dari timezone
                            const formatDate = (date) => {
                                const year = date.getFullYear();
                                const month = String(date.getMonth() + 1).padStart(2, '0'); // getMonth() 0-indexed
                                const day = String(date.getDate()).padStart(2, '0');
                                return `${year}-${month}-${day}`;
                            };

                            // Gunakan fungsi format yang baru
                            startDateInput.value = formatDate(selectedDates[0]);
                            endDateInput.value = formatDate(selectedDates[1]);

                        } else {
                            // Kosongkan jika pilihan tidak lengkap
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
