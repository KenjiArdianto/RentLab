<x-layout>
     <div class="container-fluid mt-4 pe-0">
        <div class="d-flex justify-content-center">

            {{-- Kolom 1: SIDEBAR FILTER --}}
            <div class="flex-shrink-0 p-3 bg-light" style="width: 22vw; overflow-y:visible; overflow-x:hidden;">
                <div>
                    {{-- FIX: Action menunjuk ke route, method GET tidak perlu @csrf --}}
                    <form action="{{ route('vehicle.catalog', $vehicle) }}" method="GET">
                        @csrf
                        <h5 class="d-flex justify-content-center">Filter Kendaraan</h5>
                        <hr>

                        {{-- FIX: 'name' untuk radio button harus sama dan bukan array --}}
                        <div class="btn-group w-100 mb-3" role="group">
                            <input type="radio" class="btn-check" name="Tipe_Kendaraan" id="opsiMobil" value="Mobil" {{ old('Tipe_Kendaraan', request('Tipe_Kendaraan', 'Mobil')) == 'Mobil' ? 'checked' : '' }}>
                            <label class="btn btn-outline-primary" for="opsiMobil" onclick="tampilkanForm('mobil')">Mobil</label>

                            <input type="radio" class="btn-check" name="Tipe_Kendaraan" id="opsiMotor" value="Motor" {{ old('Tipe_Kendaraan', request('Tipe_Kendaraan')) == 'Motor' ? 'checked' : '' }}>
                            <label class="btn btn-outline-primary" for="opsiMotor" onclick="tampilkanForm('motor')">Motor</label>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-center">
                                <div id="kalender-inline"></div>
                            </div>
                        </div>
                        
                        {{-- FIX: Semua ID pada checkbox dibuat UNIK untuk menghindari error JS --}}
                        <div class="mt-4">
                            @php $jenisKendaraan = old('Jenis_Kendaraan', request('Jenis_Kendaraan', [])); @endphp
                            @foreach(['SUV', 'MPV', 'City Car', 'Sedan', 'Pickup', 'Van / Minibus', 'Listrik'] as $jenis)
                                @php $id = 'check-' . Str::slug($jenis); @endphp
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="Jenis_Kendaraan[]" value="{{ $jenis }}" id="{{ $id }}" {{ in_array($jenis, $jenisKendaraan) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="{{ $id }}">{{ $jenis }}</label>
                                </div>
                            @endforeach
                        </div>

                        <hr>

                        <div class="d-flex col-12">
                            @php $jenisTransmisi = old('Jenis_Transmisi', request('Jenis_Transmisi', [])); @endphp
                            <div id="wrapper-manual" class="col-6">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="Jenis_Transmisi[]" id="checkManual" value="Manual" {{ in_array('Manual', $jenisTransmisi) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="checkManual">Manual</label>
                                </div>
                            </div>
                            <div id="wrapper-matic" class="col-6">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="Jenis_Transmisi[]" id="checkMatic" value="Matic" {{ in_array('Matic', $jenisTransmisi) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="checkMatic">Matic</label>
                                </div>
                            </div>
                            <div id="wrapper-kopling" style="display: none;">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="Jenis_Transmisi[]" id="checkKopling" value="Kopling" {{ in_array('Kopling', $jenisTransmisi) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="checkKopling">Kopling</label>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <input type="hidden" name="start_date" id="start_date_hidden" value="{{ old('start_date', request('start_date')) }}">
                        <input type="hidden" name="end_date" id="end_date_hidden" value="{{ old('end_date', request('end_date')) }}">

                        {{-- ... Dropdown Lokasi (pastikan ID di dalamnya juga unik) ... --}}

                        <p class="p-2 pb-1 mb-2 fs-5">Jangkauan Harga</p>

                        <div class="ps-2 pe-2 d-flex pb-1 mb-1 align-items-center justify-content-center">
                            {{-- FIX: Menggunakan old() dan @error untuk konsistensi --}}
                            <input type="text" class="form-control @error('min_price') is-invalid @enderror" name="min_price" placeholder="Min" value="{{ old('min_price', request('min_price', 0)) }}">
                            <img src="{{ asset('page_assets/arrow.png') }}" alt="->" class="m-2" height="20px">
                            <input type="text" class="form-control @error('max_price') is-invalid @enderror" name="max_price" placeholder="Max" value="{{ old('max_price', request('max_price')) }}">
                        </div>
                        
                        <div class="ps-2 pe-2 mb-3">
                            @error('min_price') <div class="text-danger small">{{ $message }}</div> @enderror
                            @error('max_price') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>

                        <div class="container-fluid d-flex p-0">
                            <a href="{{ route('vehicle.catalog') }}" class="container-fluid btn btn-secondary m-2">Reset</a>
                            <button type="submit" class="container-fluid btn btn-primary m-2">Filter</button>
                        </div>

                        @if(request()->has('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                    </form>
                </div>
            </div>

            {{-- Kolom 2: KONTEN UTAMA (Lebar Fleksibel + Bisa Scroll) --}}
            <div class="flex-grow-1 p-3">
                <div class="row g-4">
                    @forelse ($vehicle as $vehicle_item)
                        <div class="col-12 col-md-6 col-lg-4 col-xl-3 p-1">
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
                {{-- Ganti blok pagination lama dengan yang baru ini --}}
                <div class="mt-4">

                    {{-- Bagian 1: Teks Informasi "Showing..." --}}
                    {{-- Kita buat manual menggunakan data dari Paginator --}}
                    <p class="text-center text-muted small">
                        Showing {{ $vehicle->firstItem() }} to {{ $vehicle->lastItem() }} of {{ $vehicle->total() }} results
                    </p>

                    {{-- Bagian 2: Link Halaman (1, 2, 3...) --}}
                    {{-- Kita tetap menggunakan links() tapi hanya untuk tombolnya --}}
                    <div class="d-flex justify-content-center">
                        {{ $vehicle->links() }}
                    </div>
                    
                </div>
            </div>
        </div>

    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Ambil elemen input tersembunyi
            const startDateInput = document.getElementById('start_date_hidden');
            const endDateInput = document.getElementById('end_date_hidden');

            // Siapkan tanggal default (logikanya tetap sama)
            let defaultDateRange = [];
            if (startDateInput.value && endDateInput.value) {
                defaultDateRange = [startDateInput.value, endDateInput.value];
            } else {
                const today = new Date();
                const nextWeek = new Date();
                nextWeek.setDate(today.getDate() + 7);
                defaultDateRange = [today, nextWeek];
            }

            // Inisialisasi Flatpickr pada DIV, bukan lagi pada INPUT
            flatpickr("#kalender-inline", { // <--- PERUBAHAN 1: Target diubah ke #kalender-inline
                
                inline: true, // <--- PERUBAHAN 2: INI KUNCINYA! Membuat kalender selalu terlihat

                mode: "range",
                dateFormat: "Y-m-d",
                minDate: "today",
                locale: "id",
                defaultDate: defaultDateRange,
                
                // Opsi 'altInput' dan 'altFormat' dihapus karena tidak lagi relevan
                
                onChange: function(selectedDates, dateStr, instance) {
                    if (selectedDates.length === 2) {
                        const formatDate = (date) => date.toISOString().split('T')[0];
                        
                        startDateInput.value = formatDate(selectedDates[0]);
                        endDateInput.value = formatDate(selectedDates[1]);
                    }
                }
            });
        });

        // Fungsi ini akan dipanggil setiap kali tombol Mobil/Motor diklik
        function tampilkanForm(tipe) {
            // Ambil semua elemen wrapper berdasarkan ID unik mereka
            var wrapperManual = document.getElementById('wrapper-manual');
            var wrapperMatic = document.getElementById('wrapper-matic');
            var wrapperKopling = document.getElementById('wrapper-kopling');

            // Logika IF-ELSE yang disesuaikan
            if (tipe === 'mobil') {
                // Tampilkan opsi Kopling? TIDAK. Jadi kita sembunyikan.
                wrapperKopling.style.display = 'none';

                // Atur lebar kolom untuk Manual dan Matic menjadi col-6 agar pas berdua
                wrapperManual.className = 'col-6';
                wrapperMatic.className = 'col-6';

            } else if (tipe === 'motor') {
                // Tampilkan opsi Kopling? YA.
                wrapperKopling.style.display = 'block'; // atau 'inline-block'

                // Karena sekarang ada 3 item, kita ubah lebarnya menjadi col-4 agar pas bertiga
                wrapperManual.className = 'col-4';
                wrapperMatic.className = 'col-4';
                wrapperKopling.className = 'col-4';
            }
        }
    </script>
</x-layout>