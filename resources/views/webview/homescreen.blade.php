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
    <div class="container-fluid mt-4 pe-0">
        {{-- Gunakan d-flex untuk layout sidebar dan konten --}}
        <div class="d-flex justify-content-center"> {{-- Batasi tinggi agar konten bisa scroll --}}

            {{-- Kolom 1: SIDEBAR FILTER (Lebar Tetap) --}}
            <div class="flex-shrink-0 p-3 bg-light" style="width: 22vw; overflow-y:visible; overflow-x:hidden; ">
                <div>
                    <form action="{{-- GANTI DENGAN ROUTE FILTER ANDA --}}" method="GET">
                        @csrf
                        <h5 class="d-flex justify-content-center">Filter Kendaraan</h5>
                        <hr>
    
                        <div class="btn-group w-100 mb-3" role="group">
                            <input type="radio" class="btn-check" name="Tipe_Kendaraan[]" id="opsiMobil" value="Mobil" {{ request('tipe_kendaraan', 'mobil') == 'mobil' ? 'checked' : '' }}>
                            <label class="btn btn-outline-primary" for="opsiMobil" onclick="tampilkanForm('mobil')">Mobil</label>
    
                            <input type="radio" class="btn-check" name="Tipe_Kendaraan[]" id="opsiMotor" value="Motor" {{ request('tipe_kendaraan') == 'motor' ? 'checked' : '' }}>
                            <label class="btn btn-outline-primary" for="opsiMotor" onclick="tampilkanForm('motor')">Motor</label>
                        </div>
    
                        {{-- Filter Periode Sewa --}}
                        <div class="mb-3">
                            {{-- Ini adalah wadah kosong tempat kalender akan "digambar" --}}
                            <div class="d-flex justify-content-center">
                                <div id="kalender-inline"></div>
                            </div>
                        </div>
                        
                        <div class = "mt-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="Jenis_Kendaraan[]" value="SUV" id="checkDefault">
                                <label class="form-check-label" for="checkDefault">
                                    SUV
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="Jenis_Kendaraan[]" value="MPV" id="checkDefault">
                                <label class="form-check-label" for="checkDefault">
                                    MPV
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="Jenis_Kendaraan[]" value="City Car" id="checkDefault">
                                <label class="form-check-label" for="checkDefault">
                                    City Car
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="Jenis_Kendaraan[]" value="Sedan" id="checkDefault">
                                <label class="form-check-label" for="checkDefault">
                                    Sedan
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="Jenis_Kendaraan[]" value="Pickup" id="checkDefault">
                                <label class="form-check-label" for="checkDefault">
                                    Pickup
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="Jenis_Kendaraan[]" value="Van / Minibus" id="checkDefault">
                                <label class="form-check-label" for="checkDefault">
                                    Van / Minibus
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="Jenis_Kendaraan[]" value="Listrik" id="checkDefault">
                                <label class="form-check-label" for="checkDefault">
                                    Listrik
                                </label>
                            </div>
                        </div>
    
                        <hr>
    
                        <div class="d-flex col-12">

                            <div id="wrapper-manual" class="col-6">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="Jenis_Transmisi[]" id="radioManual" value="Manual">
                                    <label class="form-check-label" for="radioManual">Manual</label>
                                </div>
                            </div>

                            <div id="wrapper-matic" class="col-6">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="Jenis_Transmisi[]" id="radioMatic" value="Matic">
                                    <label class="form-check-label" for="radioMatic">Matic</label>
                                </div>
                            </div>

                            <div id="wrapper-kopling" style="display: none;">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="Jenis_Transmisi[]" id="radioKopling" value="Kopling">
                                    <label class="form-check-label" for="radioKopling">Kopling</label>
                                </div>
                            </div>

                        </div>
    
                        <hr>
    
                        {{-- Input tersembunyi untuk tanggal --}}
                        <input type="hidden" name="start_date" id="start_date_hidden" value="{{ request('start_date') }}">
                        <input type="hidden" name="end_date" id="end_date_hidden" value="{{ request('end_date') }}">
    
                        <div class="input-group mb-3">
                            <button class="btn btn-light dropdown-toggle w-100 d-flex align-items-center justify-content-between" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <p class="p-0 m-0 fs-5">
                                    Lokasi
                                </p>
                            </button>
                            <ul class="dropdown-menu w-100 ps-3">
                                <li>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="Tempat[]" value="Jakarta Barat" id="checkDefault">
                                        <label class="form-check-label" for="checkDefault">
                                            Jakarta Barat
                                        </label>
                                    </div>
                                </li>
                                <li>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="Tempat[]" value="Jakarta Utara" id="checkDefault">
                                        <label class="form-check-label" for="checkDefault">
                                            Jakarta Utara
                                        </label>
                                    </div>
                                </li><li>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="Tempat[]" value="Jakarta Pusat" id="checkDefault">
                                        <label class="form-check-label" for="checkDefault">
                                            Jakarta Pusat
                                        </label>
                                    </div>
                                </li><li>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="Tempat[]" value="Jakarta Selatan" id="checkDefault">
                                        <label class="form-check-label" for="checkDefault">
                                            Jakarta Selatan
                                        </label>
                                    </div>
                                </li><li>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="Tempat[]" value="Jakarta Timur" id="checkDefault">
                                        <label class="form-check-label" for="checkDefault">
                                            Jakarta Timur
                                        </label>
                                    </div>
                                </li>
                            </ul>
                        </div>
    
                        <p class="p-2 pb-1 mb-2 fs-5">Jangkauan Harga</p>
    
                        <div class = "ps-2 pe-2 d-flex pb-3 mb-5 align-items-center justify-content-center">
                            <input type="number" class="form-control" name="min_price" placeholder="Min" value="{{ request('min_price') }}">
                            <img src="/page_assets/arrow.png" alt="" class="m-2" height="20px">
                            <input type="number" class="form-control" name="max_price" placeholder="Max" value="{{ request('max_price') }}">
                        </div>
    
    
                        <div class="container-fluid">
                            <button type="submit" class="container-fluid btn btn-primary mb-2">Filter</button>
                        </div>
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
    

    {{-- =================================== --}}
    {{-- SCRIPT FLATPCIKR YANG DIPERBAIKI --}}
    {{-- =================================== --}}
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