<x-layout>
    {{-- Main content area with responsive row/column structure --}}
    <div class="container mt-2">
        <div class="row p-1">

            {{-- =================================================================== --}}
            {{-- MOBILE HEADER                                                   --}}
            {{-- =================================================================== --}}
            <div class="d-flex justify-content-between align-items-center mb-1 d-xxl-none">

                {{-- Kiri: Placeholder tak terlihat untuk menyeimbangkan tombol di kanan --}}
                <div style="width: 35px;"></div>

                {{-- Tengah: Judul "Product" --}}
                <h5 class="p-0 m-0">@lang('app.filter.product')</h5>

                {{-- Kanan: Tombol Filter --}}
                <button class="btn btn-light border rounded-circle d-flex align-items-center justify-content-center p-0"
                        style="width: 35px; height: 35px;"
                        type="button"
                        data-bs-toggle="offcanvas"
                        data-bs-target="#filterSidebar"
                        aria-controls="filterSidebar">
                    <img src="{{ asset('page_assets/filter.svg') }}" alt="Filter" style="width:18px; height:18px;">
                </button>

            </div>
            <hr class="mt-1 mb-2 d-xxl-none">
            {{-- =================================================================== --}}
            {{-- AKHIR DARI MOBILE HEADER                                        --}}
            {{-- =================================================================== --}}


            {{-- Kolom 1: SIDEBAR FILTER --}}
            <aside class="col-xxl-3 bg-light p-2 offcanvas-xxl offcanvas-start" tabindex="-1" id="filterSidebar" aria-labelledby="filterSidebarLabel">
                {{-- Header for mobile offcanvas view --}}
                <div class="offcanvas-header d-xxl-none">
                    <h5 class="offcanvas-title" id="filterSidebarLabel">@lang('app.filter.title')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#filterSidebar" aria-label="Close"></button>
                </div>

                {{-- Body of the offcanvas, containing the form --}}
                <div class="offcanvas-body">
                    {{-- Form action dan link reset menunjuk ke route 'vehicle.catalog' --}}
                    <form action="{{ route('vehicle.catalog') }}" method="GET" id="filterForm">
                        @php
                            $activeType = request('Tipe_Kendaraan', 'Car');
                            $activeTransmissions = request('Jenis_Transmisi', []);
                            $activeCategories = request('Jenis_Kendaraan', []);
                        @endphp

                        {{-- Title for desktop view --}}
                        <h5 class="d-none d-xxl-block text-center">@lang('app.filter.title')</h5>
                        <hr class="d-none d-xxl-block m-2">

                        {{-- Hidden input for search query persistence --}}
                        @if(request()->has('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif

                        {{-- Filter Tipe Kendaraan (Mobil/Motor) --}}
                        <div class="btn-group w-100 mb-3" role="group">
                            <input type="radio" class="btn-check" name="Tipe_Kendaraan" id="opsiMobil" value="Car" @checked($activeType == 'Car')>
                            <label class="btn btn-outline-primary" for="opsiMobil">@lang('app.vehicle_type.car')</label>

                            <input type="radio" class="btn-check" name="Tipe_Kendaraan" id="opsiMotor" value="Motor" @checked($activeType == 'Motor')>
                            <label class="btn btn-outline-primary" for="opsiMotor">@lang('app.vehicle_type.motorcycle')</label>
                        </div>

                        {{-- Flatpickr calendar --}}
                        <div class="d-flex justify-content-center">
                            <div id="kalender-inline" class="mb-3"></div>
                        </div>

                        <input type="hidden" name="start_date" id="start_date_hidden" value="{{ request('start_date') }}">
                        <input type="hidden" name="end_date" id="end_date_hidden" value="{{ request('end_date') }}">

                        <hr>

                        {{-- Filter Jenis Kendaraan (Dinamis dari Controller) --}}
                        <p class="p-2 pb-1 mb-0 fs-6 fw-semibold">@lang('app.filter.vehicle_type')</p>
                        <div id="wrapper-kategori-mobil" class="px-2">
                            @foreach($carCategories as $jenis)
                                @php $id = 'check-mobil-' . Str::slug($jenis); @endphp
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="Jenis_Kendaraan[]" value="{{ $jenis }}" id="{{ $id }}" @checked(in_array($jenis, $activeCategories))>
                                    <label class="form-check-label" for="{{ $id }}">{{ $jenis }}</label>
                                </div>
                            @endforeach
                        </div>
                        <div id="wrapper-kategori-motor" class="px-2" style="display: none;">
                            @foreach($motorcycleCategories as $jenis)
                                @php $id = 'check-motor-' . Str::slug($jenis); @endphp
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="Jenis_Kendaraan[]" id="{{ $id }}" value="{{ $jenis }}" @checked(in_array($jenis, $activeCategories))>
                                    <label class="form-check-label" for="{{ $id }}">{{ $jenis }}</label>
                                </div>
                            @endforeach
                        </div>
                        <hr>

                        {{-- Filter Transmisi (Dinamis) --}}
                        <p class="p-2 pb-1 mb-0 fs-6 fw-semibold">@lang('app.filter.transmission')</p>
                        <div class="row mb-3 px-2">
                            <div id="wrapper-manual" class="col-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="Jenis_Transmisi[]" id="checkManual" value="Manual" @checked(in_array('Manual', $activeTransmissions))>
                                    <label class="form-check-label" for="checkManual">@lang('app.transmission.manual')</label>
                                </div>
                            </div>
                            <div id="wrapper-matic" class="col-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="Jenis_Transmisi[]" id="checkAutomatic" value="Automatic" @checked(in_array('Automatic', $activeTransmissions))>
                                    <label class="form-check-label" for="checkAutomatic">@lang('app.transmission.automatic')</label>
                                </div>
                            </div>
                            <div id="wrapper-kopling" class="col-4" style="display: none;">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="Jenis_Transmisi[]" id="checkKopling" value="Kopling" @checked(in_array('Kopling', $activeTransmissions))>
                                    <label class="form-check-label" for="checkKopling">@lang('app.transmission.clutch')</label>
                                </div>
                            </div>
                        </div>
                        <hr>

                        {{-- Filter Jangkauan Harga --}}
                        <p class="p-2 pb-1 mb-2 fs-5">@lang('app.filter.price_range')</p>
                        <div class="ps-2 pe-2 d-flex pb-1 mb-1 align-items-center">
                            <input type="number" class="form-control @error('min_price') is-invalid @enderror" name="min_price" placeholder="@lang('app.placeholders.min_price')" value="{{ request('min_price') }}">
                            <span class="px-2">-</span>
                            <input type="number" class="form-control @error('max_price') is-invalid @enderror" name="max_price" placeholder="@lang('app.placeholders.max_price')" value="{{ request('max_price') }}">
                        </div>
                        <div class="ps-2 pe-2 mb-3">
                            @error('min_price') <div class="text-danger small">{{ $message }}</div> @enderror
                            @error('max_price') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="d-flex p-0 mt-3">
                            <a href="{{ route('vehicle.catalog') }}" class="btn btn-secondary w-100 m-2">@lang('app.filter.buttons.reset')</a>
                            <button type="submit" class="btn btn-primary w-100 m-2">@lang('app.filter.buttons.filter')</button>
                        </div>
                    </form>
                </div>
            </aside>

            {{-- Kolom 2: KONTEN UTAMA --}}
            <main class="col-xxl-9">

                {{-- Menampilkan info hasil pencarian jika ada --}}
                @if(request('search'))
                    <div class="alert alert-info" role="alert">
                      @lang('app.results.showing_for', ['term' => e(request('search'))])
                    </div>
                @endif

                {{-- Grid for vehicle cards --}}
                <div class="row g-2">
                    @forelse ($vehicle as $vehicle_item)
                        <div class="col-6 col-md-4 col-lg-3">
                            <x-card :href="route('vehicle.detail', $vehicle_item->id)" :vehicle_item="$vehicle_item" />
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-warning text-center">
                                @lang('app.results.empty')
                            </div>
                        </div>
                    @endforelse
                </div>

                {{-- Pagination. Teks "Showing results" diterjemahkan otomatis dari file lang/xx/pagination.php --}}
                <div class="mt-4">
                    <div class="d-flex justify-content-center">
                        {{ $vehicle->withQueryString()->links() }}
                    </div>
                </div>
            </main>
        </div>
    </div>

    <div class="m-lg-0" style="margin:8vh"></div>

    @push('scripts')
    <script>
        // SCRIPT IDENTIK DENGAN HOMESCREEN.BLADE.PHP
        function resetAllFilters(flatpickrInstance) {
            document.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);
            const minPriceInput = document.querySelector('input[name="min_price"]');
            const maxPriceInput = document.querySelector('input[name="max_price"]');
            if (minPriceInput) minPriceInput.value = '';
            if (maxPriceInput) maxPriceInput.value = '';
            const startDateInput = document.getElementById('start_date_hidden');
            const endDateInput = document.getElementById('end_date_hidden');
            if(startDateInput) startDateInput.value = '';
            if(endDateInput) endDateInput.value = '';
            if (flatpickrInstance) {
                flatpickrInstance.clear();
            }
        }

        function tampilkanForm(tipe) {
            const wrappers = {
                manual: document.getElementById('wrapper-manual'),
                matic: document.getElementById('wrapper-matic'),
                kopling: document.getElementById('wrapper-kopling'),
                kategoriMobil: document.getElementById('wrapper-kategori-mobil'),
                kategoriMotor: document.getElementById('wrapper-kategori-motor')
            };

            if (tipe === 'Car') {
                wrappers.kopling.style.display = 'none';
                wrappers.manual.className = 'col-6';
                wrappers.matic.className = 'col-6';
                wrappers.kategoriMobil.style.display = 'block';
                wrappers.kategoriMotor.style.display = 'none';
            } else if (tipe === 'Motor') {
                wrappers.kopling.style.display = 'block';
                wrappers.manual.className = 'col-4';
                wrappers.matic.className = 'col-4';
                wrappers.kopling.className = 'col-4';
                wrappers.kategoriMobil.style.display = 'none';
                wrappers.kategoriMotor.style.display = 'block';
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const filterForm = document.getElementById('filterForm');
            if (!filterForm) return;

            const radios = filterForm.querySelectorAll('input[name="Tipe_Kendaraan"]');
            const startDateInput = document.getElementById('start_date_hidden');
            const endDateInput = document.getElementById('end_date_hidden');
            let calendarInstance = null;

            const tipeAktifSaatIni = '{{ request("Tipe_Kendaraan", "Car") }}';
            tampilkanForm(tipeAktifSaatIni);

            if (document.getElementById('kalender-inline')) {
                let tanggalDefault = [];
                if (startDateInput && endDateInput && startDateInput.value && endDateInput.value) {
                    tanggalDefault = [startDateInput.value, endDateInput.value];
                }

                calendarInstance = flatpickr("#kalender-inline", {
                    inline: true,
                    mode: "range",
                    dateFormat: "Y-m-d",
                    minDate: "today",
                    locale: '{{ app()->getLocale() }}', // LOKALISASI DINAMIS
                    defaultDate: tanggalDefault,
                    onChange: function(selectedDates) {
                        if (selectedDates.length === 2) {
                            // FUNGSI BARU YANG BENAR (Anti-Timezone)
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

            radios.forEach(radio => {
                radio.addEventListener('change', function() {
                    resetAllFilters(calendarInstance);
                    tampilkanForm(this.value);
                    filterForm.submit();
                });
            });
        });
    </script>
    @endpush
</x-layout>
