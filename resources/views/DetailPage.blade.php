<x-layout>

    {{-- Hapus CSS DetailPage.css jika tidak lagi diperlukan setelah perubahan design --}}
    {{--
    <link rel="stylesheet" href="{{ asset('css/DetailPage.css') }}"> --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    {{-- Flatpickr CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css"> {{-- Tema biru
    yang mirip referensi --}}

    <div class="main-content mt-4">
        {{-- Thumbnails --}}
        <div class="thumbnail-container">
            <div class="h-20 thumbnail-item active-thumbnail" data-bs-slide-to="0">
                <img src="{{ $idVehicle->main_image }}" class="w-100 shadow-sm rounded-1" alt="Main Product Image 1">
            </div>

            @foreach ($getVehicleimagesById as $index => $imageById)
                <div class="h-20 thumbnail-item" data-bs-slide-to="{{ $index + 1 }}">
                    <img src="{{ $imageById->image }}" class="w-100 shadow-sm rounded-1"
                        alt="Vehicle Image {{ $index + 2 }}">
                </div>
            @endforeach
        </div>

        {{-- Main Carousel --}}
        <div class="carousel-section mb-2">
            <div id="productCarousel" class="carousel slide" data-bs-interval="false">
                <div class="carousel-inner shadow">
                    <div class="carousel-item active m-0">
                        <img src="{{ $idVehicle->main_image }}" class="d-block w-100 rounded-1"
                            alt="Main Product Image">
                    </div>

                    @foreach ($getVehicleimagesById as $imageById)
                        <div class="carousel-item">
                            <img src="{{ $imageById->image }}" class="d-block w-100 rounded-1" alt="Vehicle Image">
                        </div>
                    @endforeach
                </div>

                <div class="container">
                    <div class="position-absolute bottom-0 end-0 m-2 pe-2">
                        <i class="bi bi-arrow-left-circle-fill" type="button" data-bs-target="#productCarousel"
                            data-bs-slide="prev" style="color: aliceblue; font-size: 2rem; cursor: pointer;"></i>
                        <i class="bi bi-arrow-right-circle-fill" type="button" data-bs-target="#productCarousel"
                            data-bs-slide="next"
                            style="color: aliceblue; font-size: 2rem; cursor: pointer; margin-left: 10px;"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Product Details --}}
        <div class="product-details-section">
            <div class="container p-2 bg-light rounded-1 detail-card">
                <div class="description">
                    <h1 class="mb-1 fw-bold">{{$idVehicle->vehicleName->name}}</h1>

                    <div class="d-flex justify-content-between align-items-center p-0">
                        <div class="d-flex justify-content-center align-items-center">
                            <i class="bi bi-geo-alt-fill me-2"></i>
                            <h4 class="text-muted m-0">{{$idVehicle->location->location}}</h4>
                        </div>

                        <div class="justify-content-end">
                            <div class="d-flex justify-content-center align-items-center">
                                <img class="justify-content-center align-items-center"
                                    src="{{asset('images/RatingStar.png')}}" alt="RatingStar" style="height: 20px">
                                @if ($rating)
                                    <h4 class="ms-2 me-3 mb-0">{{ $rating->average_rating }}/5</h4>
                                @else
                                    <h4 class="ms-2 me-3 mb-0">- /5</h4>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="price-section">
                    <h1 class="mb-0 fs-3">Rp.{{$idVehicle->price}},00<span class="fs-6 text-muted"> /hari</span></h1>
                </div>

                <div class="container">
                    <div class="row row-cols-2">
                        <p class="my-2 detail-item col d-flex mx-0 p-0 ">
                            <i class="bi bi-person-fill mx-1 pt-1"></i>
                            <span> {{$idVehicle->seats}} Seats</span>
                        </p>

                        <div class="d-flex justify-content-center align-items-center p-0">
                            <img class="mt-1 me-2" src="{{asset('images/Pedal.png')}}" alt="pedal"
                                style="width: 15px; height: 15px; ">
                            <p class="my-2 detail-item col p-0">{{$idVehicle->vehicleTransmission->transmission}}</p>
                        </div>

                        <div class="d-flex justify-content-center align-items-center p-0">
                            <i class="bi bi-car-front-fill mx-1 pt-1"></i>
                            <p class="mb-0 detail-item col p-0">
                                {{ $idVehicle->vehicleCategories->first()->category}}
                            </p>
                        </div>

                        <div class="d-flex justify-content-center align-items-center p-0">
                            <i class="bi bi-fuel-pump-fill mt-1 mx-0"></i>
                            <p class="mx-0 mb-0 detail-item col"><i class="fas fa-cogs me-2 text-secondary"></i>
                                {{$idVehicle->engine_cc}} cc</p>
                        </div>
                    </div>
                </div>

                <hr class="my-2">

                <div class="container overflow-auto card-container border border-2 rounded-2" style="height: 174px;">
                    <h5 class="mt-1 mb-2">Add date</h5>
                    <div class="input-group mb-2">
                        <span class="input-group-text" id="calendarIcon" style="cursor: pointer;"
                            aria-label="Open Calendar">
                            <i class="bi bi-calendar-plus"></i>
                        </span>
                        <input type="text" class="form-control" id="dateInput" placeholder="No date range selected"
                            aria-label="Selected date range" disabled>
                    </div>

                    <hr class="mt-3 mb-2">

                    <h5 class="mb-2">Selected Dates (Cart)</h5>
                    <div id="cartDisplay">
                        <p id="noDatesMessage" class="text-muted">No dates selected yet.</p>
                    </div>
                </div>

                <div class="modal fade" id="datePickerModal" tabindex="-1" aria-labelledby="datePickerModalLabel"
                    aria-hidden="true">
                    {{-- Ubah ukuran modal menjadi modal-lg atau atur width custom --}}
                    {{-- Kita akan gunakan modal-dialog-custom-width untuk kontrol lebih baik --}}
                    <div class="modal-dialog modal-dialog-centered modal-dialog-custom-width">
                        <div class="modal-content" style="width:700px">
                            <div class="modal-header">
                                <h5 class="modal-title" id="datePickerModalLabel">Select Date Range</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            {{-- Ganti g-3 dengan g-2 untuk jarak antar kolom yang lebih kecil --}}
                            <div class="modal-body row g-2">
                                <div class="col-12 col-md-6 text-center d-flex pe-0 justify-content-center">
                                    <div id="dateRangePicker"></div>
                                </div>
                                <div class="col-12 col-md-6 ps-0">
                                    <h6 class="text-center mt-3 mt-md-0">This Car in your cart</h6>
                                    <div class="overflow-auto container p-2" style="max-height: 250px;">
                                        @foreach ($getVehicleByIdsINCarts as $getVehicleByIdsINCart)
                                            <div
                                                class="container border rounded-2 my-2 d-flex justify-content-center align-items-center">
                                                <div class="flex-grow-1">
                                                    <div class="container">
                                                        {{ \Carbon\Carbon::parse($getVehicleByIdsINCart->start_date)->format('j M Y') }}
                                                        -
                                                        {{ \Carbon\Carbon::parse($getVehicleByIdsINCart->end_date)->format('j M Y') }}
                                                    </div>
                                                </div>

                                                <div class="m-2">
                                                    <div class="d-flex justify-content-center align-items-center">
                                                        <form action="/vehiDel/{{$getVehicleByIdsINCart->id}}"
                                                            method="POST">
                                                            <input type="hidden" name="vehicle_id"
                                                                value="{{ $getVehicleByIdsINCart->vehicle_id}}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <div class="container bg-danger p-2 rounded-1"
                                                                style="width:32px">
                                                                <button
                                                                    class="submit text-light border-0 bg-transparent p-0 w-20">
                                                                    <i class="bi bi-trash3"></i>
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-12 text-center">
                                    <small class="text-muted mt-3 d-block">Select a start date and an end date.</small>
                                    <small class="text-muted d-block">(Click the same date twice for a single-day
                                        selection)</small>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" id="saveDatesBtn">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mb-3 mt-2 d-flex justify-content-between align-items-start px-0 add-to-cart-section">
        <hr class="flex-grow-1 ms-0 me-3 mb-0">
        <form class="mb-0" id="addToCartForm" method="POST" action="/cartInput">
            @csrf
            <input type="hidden" name="vehicle_id" value="{{ $idVehicle->id }}">
            <div id="hiddenDateInputs"></div>
            <button class="btn btn-secondary bg-primary" type="submit" id="addToCartBtn">Add to
                Cart</button>
        </form>
    </div>

    <h2 class="p-2 user-review-heading">User Reviews</h2>

    @foreach ($getCommentByIdVehicle as $comment)
        <div class="px-0 p-2">
            <div class="card review-card">
                <div class="card-body row">
                    <div class="col-2 col-md-1 d-flex justify-content-end align-items-center p-0 user-avatar-container">
                        <div class="rounded-circle overflow-hidden p-0 user-avatar">
                            <img class="w-100 h-100 object-fit-cover"
                                src="{{ $comment->user->profile_picture_url ?? asset('images/default_avatar.png') }}"
                                alt="User Avatar">
                        </div>
                    </div>

                    <div class="col-10 col-md-11 ps-4 user-review-content">
                        <h4>{{$comment->user->name}}</h4>
                        <hr class="mt-1 mb-2 pe-2 review-hr">
                        <h5 class="text-muted">{{$comment->comment}}</h5>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eB0kRjXh0JMhjY6hW+ALEwIH"
        crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    {{-- Flatpickr JS --}}
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    {{-- Moment.js untuk formatting tanggal lebih mudah (opsional tapi disarankan) --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

    <script>
        const cartDateRangesFromDB = @json($cartDateRanges);
    </script>

    <script>
        $(document).ready(function () {
            const MAX_DATE_RANGES = 3;
            let selectedDateRanges = [];
            let flatpickrInstance; // Variabel untuk menyimpan instance Flatpickr
            const dateInput = $('#dateInput');
            const cartDisplay = $('#cartDisplay');
            const datePickerModalInstance = new bootstrap.Modal(document.getElementById('datePickerModal'));

            // Inisialisasi Flatpickr
            flatpickrInstance = flatpickr("#dateRangePicker", {
                mode: "range", // Mode rentang tanggal
                inline: true, // Tampilkan inline di modal
                dateFormat: "Y-m-d", // Format tanggal
                minDate: "today", // Tidak bisa memilih tanggal yang sudah lewat
                enable: [ // Ini akan berisi tanggal yang tidak disabled
                    {
                        from: "today", // Dari hari ini
                        to: "2100-01-01" // Sampai tanggal jauh di masa depan
                    }
                ],
                onDayCreate: function (dObj, dStr, fp, dayElem) {
                    const date = new Date(dayElem.dateObj.getFullYear(), dayElem.dateObj.getMonth(), dayElem.dateObj.getDate());
                    const today = new Date();
                    const normalizedToday = new Date(today.getFullYear(), today.getMonth(), today.getDate());

                    // Disable tanggal yang sudah lewat dan terapkan gaya kustom
                    if (date < normalizedToday) {
                        dayElem.classList.add('past-date-disabled'); // Custom class for styling
                        dayElem.setAttribute('title', 'Tanggal sudah lewat');
                    }

                    // Disable tanggal yang sudah ada di selectedDateRanges
                    for (const range of selectedDateRanges) {
                        const rangeStart = new Date(range.startDate.getFullYear(), range.startDate.getMonth(), range.startDate.getDate());
                        const rangeEnd = new Date(range.endDate.getFullYear(), range.endDate.getMonth(), range.endDate.getDate());

                        if (date >= rangeStart && date <= rangeEnd) {
                            dayElem.classList.add('flatpickr-disabled');
                            dayElem.setAttribute('title', 'Sudah dipilih (saat ini)');
                            break;
                        }
                    }

                    // Disable tanggal yang sudah ada di cartDateRangesFromDB (dari database)
                    if (typeof cartDateRangesFromDB !== 'undefined') {
                        for (const dbRange of cartDateRangesFromDB) {
                            const dbStart = new Date(moment(dbRange.start_date).format('YYYY-MM-DD'));
                            const dbEnd = new Date(moment(dbRange.end_date).format('YYYY-MM-DD'));

                            const normalizedDbStart = new Date(dbStart.getFullYear(), dbStart.getMonth(), dbStart.getDate());
                            const normalizedDbEnd = new Date(dbEnd.getFullYear(), dbEnd.getMonth(), dbEnd.getDate());

                            if (date >= normalizedDbStart && date <= normalizedDbEnd) {
                                dayElem.classList.add('flatpickr-disabled');
                                dayElem.setAttribute('title', 'Sudah dipesan di keranjang');
                                break;
                            }
                        }
                    }
                },
                onChange: function (selectedDates, dateStr, instance) {
                    if (selectedDates.length === 2) {
                        const start = moment(selectedDates[0]).format('YYYY-MM-DD');
                        const end = moment(selectedDates[1]).format('YYYY-MM-DD');
                        dateInput.val(`${start} - ${end}`);
                    } else if (selectedDates.length === 1) {
                        dateInput.val(`Mulai: ${moment(selectedDates[0]).format('YYYY-MM-DD')}`);
                    } else {
                        dateInput.val('No date range selected');
                    }
                }
            });

            $('#calendarIcon').on('click', function () {
                if (selectedDateRanges.length >= MAX_DATE_RANGES) {
                    alert(`Anda hanya dapat memilih hingga ${MAX_DATE_RANGES} rentang tanggal.`);
                    return;
                }

                dateInput.val('No date range selected');
                flatpickrInstance.clear(); // Bersihkan pilihan saat membuka modal
                flatpickrInstance.redraw(); // Redraw untuk memperbarui disabled dates
                datePickerModalInstance.show();
            });

            $('#saveDatesBtn').on('click', function () {
                const selectedDates = flatpickrInstance.selectedDates;

                if (selectedDates.length === 2) {
                    let startDate = selectedDates[0];
                    let endDate = selectedDates[1];

                    // Pastikan startDate selalu lebih kecil atau sama dengan endDate
                    if (startDate > endDate) {
                        [startDate, endDate] = [endDate, startDate];
                    }

                    // Normalisasi tanggal untuk perbandingan tanpa waktu
                    const newStart = new Date(startDate.getFullYear(), startDate.getMonth(), startDate.setDate(startDate.getDate()));
                    const newEnd = new Date(endDate.getFullYear(), endDate.getMonth(), endDate.setDate(endDate.getDate()));

                    // Check for overlap with existing selectedDateRanges (in UI)
                    const isOverlapping = selectedDateRanges.some(existingRange => {
                        const existingStart = new Date(existingRange.startDate.getFullYear(), existingRange.startDate.getMonth(), existingRange.startDate.getDate());
                        const existingEnd = new Date(existingRange.endDate.getFullYear(), existingRange.endDate.getMonth(), existingRange.endDate.getDate());
                        return (newStart <= existingEnd && newEnd >= existingStart);
                    });

                    // Check for overlap with cartDateRangesFromDB (from database)
                    const isOverlappingWithDB = cartDateRangesFromDB.some(dbRange => {
                        const dbStart = new Date(moment(dbRange.start_date).format('YYYY-MM-DD'));
                        const dbEnd = new Date(moment(dbRange.end_date).format('YYYY-MM-DD'));
                        return (newStart <= dbEnd && newEnd >= dbStart);
                    });

                    // Check if selected dates are past dates
                    const today = new Date();
                    const normalizedToday = new Date(today.getFullYear(), today.getMonth(), today.getDate());
                    if (newStart < normalizedToday || newEnd < normalizedToday) {
                        alert('Tanggal yang dipilih mencakup tanggal yang sudah lewat. Harap pilih tanggal dari hari ini dan seterusnya.');
                        return;
                    }


                    if (isOverlapping || isOverlappingWithDB) {
                        alert('Rentang tanggal yang dipilih tumpang tindih dengan rentang yang sudah dipilih sebelumnya atau tanggal yang sudah dipesan. Harap pilih tanggal yang tidak tumpang tindih.');
                        return;
                    }

                    selectedDateRanges.push({
                        startDate: new Date(startDate),
                        endDate: new Date(endDate)
                    });

                    selectedDateRanges.sort((a, b) => a.startDate - b.startDate); // Urutkan tanggal

                    datePickerModalInstance.hide();
                    updateCartDisplay();
                    flatpickrInstance.redraw(); // Perbarui tampilan Flatpickr setelah menambahkan tanggal baru
                } else if (selectedDates.length === 1) {
                    // Jika hanya satu tanggal dipilih, anggap itu sebagai rentang satu hari
                    let singleDate = selectedDates[0];
                    const newSingleDate = new Date(singleDate.getFullYear(), singleDate.getMonth(), singleDate.getDate());

                    // Check for overlap with existing selectedDateRanges (in UI)
                    const isOverlappingSingle = selectedDateRanges.some(existingRange => {
                        const existingStart = new Date(existingRange.startDate.getFullYear(), existingRange.startDate.getMonth(), existingRange.startDate.getDate());
                        const existingEnd = new Date(existingRange.endDate.getFullYear(), existingRange.endDate.getMonth(), existingRange.endDate.getDate());
                        return (newSingleDate >= existingStart && newSingleDate <= existingEnd);
                    });

                    // Check for overlap with cartDateRangesFromDB (from database)
                    const isOverlappingSingleWithDB = cartDateRangesFromDB.some(dbRange => {
                        const dbStart = new Date(moment(dbRange.start_date).format('YYYY-MM-DD'));
                        const dbEnd = new Date(moment(dbRange.end_date).format('YYYY-MM-DD'));
                        return (newSingleDate >= dbStart && newSingleDate <= dbEnd);
                    });

                    // Check if selected date is a past date
                    const today = new Date();
                    const normalizedToday = new Date(today.getFullYear(), today.getMonth(), today.getDate());
                    if (newSingleDate < normalizedToday) {
                        alert('Tanggal yang dipilih adalah tanggal yang sudah lewat. Harap pilih tanggal dari hari ini dan seterusnya.');
                        return;
                    }


                    if (isOverlappingSingle || isOverlappingSingleWithDB) {
                        alert('Tanggal yang dipilih tumpang tindih dengan rentang yang sudah dipilih sebelumnya atau tanggal yang sudah dipesan. Harap pilih tanggal yang tidak tumpang tindih.');
                        return;
                    }

                    selectedDateRanges.push({
                        startDate: new Date(singleDate),
                        endDate: new Date(singleDate)
                    });

                    selectedDateRanges.sort((a, b) => a.startDate - b.startDate);

                    datePickerModalInstance.hide();
                    updateCartDisplay();
                    flatpickrInstance.redraw();
                }
                else {
                    alert('Harap pilih rentang tanggal.');
                }
            });

            $('#addToCartBtn').on('click', function () {
                if (selectedDateRanges.length === 0) {
                    alert("Belum ada tanggal yang dipilih. Klik ikon kalender untuk menambahkan tanggal.");
                }
            });

            function updateCartDisplay() {
                cartDisplay.empty();

                if (selectedDateRanges.length === 0) {
                    cartDisplay.append('<p id="noDatesMessage" class="text-muted">No dates selected yet.</p>');
                } else {
                    const noDatesMsgElem = $('#noDatesMessage');
                    if (noDatesMsgElem.length) {
                        noDatesMsgElem.remove();
                    }

                    selectedDateRanges.forEach((range, index) => {
                        const start = moment(range.startDate).format('D MMM YYYY');
                        const end = moment(range.endDate).format('D MMM YYYY');
                        const dateRangeText = `${start} - ${end}`;

                        cartDisplay.append(`
                            <div class="cart-item d-flex justify-content-between align-items-center mb-2 p-2 border rounded" data-index="${index}">
                                <p class="mb-0"><strong>${index + 1}. </strong> ${dateRangeText}</p>
                                <button type="button" class="btn btn-danger btn-sm delete-cart-item" data-index="${index}" aria-label="Delete Selection ${index + 1}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                        <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H9.5a1 1 0 0 1 1 1v1H14a1 1 0 0 1 1 1v1zM2.5 3V2h11v1h-11z"/>
                                    </svg>
                                </button>
                            </div>
                        `);
                    });

                    cartDisplay.off('click', '.delete-cart-item').on('click', '.delete-cart-item', function () {
                        const indexToDelete = $(this).data('index');
                        selectedDateRanges.splice(indexToDelete, 1);
                        updateCartDisplay();
                        flatpickrInstance.redraw(); // Perbarui tampilan Flatpickr setelah menghapus tanggal
                    });
                }
            }
            updateCartDisplay();

            $('#addToCartForm').on('submit', function (e) {
                const hiddenInputContainer = $('#hiddenDateInputs');
                hiddenInputContainer.empty();

                if (selectedDateRanges.length === 0) {
                    e.preventDefault();
                    alert("Belum ada tanggal yang dipilih.");
                    return;
                }

                selectedDateRanges.forEach((range, index) => {
                    const start = moment(range.startDate).format('YYYY-MM-DD');
                    const end = moment(range.endDate).format('YYYY-MM-DD');

                    hiddenInputContainer.append(`
                        <input type="hidden" name="date_ranges[${index}][start_date]" value="${start}">
                        <input type="hidden" name="date_ranges[${index}][end_date]" value="${end}">
                    `);
                });
            });

        });

        document.addEventListener('DOMContentLoaded', function () {
            var productCarousel = document.getElementById('productCarousel');
            var carousel = new bootstrap.Carousel(productCarousel, {
                interval: false
            });

            var thumbnails = document.querySelectorAll('.thumbnail-item');

            function updateActiveThumbnail(activeIndex) {
                thumbnails.forEach(function (thumbnail, index) {
                    if (index === activeIndex) {
                        thumbnail.classList.add('active-thumbnail');
                    } else {
                        thumbnail.classList.remove('active-thumbnail');
                    }
                });
            }

            productCarousel.addEventListener('slid.bs.carousel', function (event) {
                updateActiveThumbnail(event.to);
            });

            thumbnails.forEach(function (thumbnail) {
                thumbnail.addEventListener('click', function () {
                    const slideToIndex = parseInt(this.getAttribute('data-bs-slide-to'));
                    if (!isNaN(slideToIndex)) {
                        carousel.to(slideToIndex);
                    }
                });
            });

            updateActiveThumbnail(0);
        });
    </script>


    <style>
        /* Styling Flatpickr agar mirip referensi */
        .flatpickr-calendar {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;

            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            /* Sedikit bayangan */
            border-radius: 8px;
            /* Sudut membulat */
            padding: 10px;
        }

        /* Mengatasi lebar Flatpickr agar tidak meluap */
        /* Ini penting untuk menjaga agar kalender tetap dalam kolomnya */
        .flatpickr-calendar.inline {
            width: 100%;
            /* Memastikan kalender mengisi 100% dari kolom parent-nya */
            max-width: max-content;
            /* Batasi lebar maksimum agar tidak terlalu besar di desktop */
            margin: auto;
            /* Pusatkan jika lebar tidak penuh */
        }

        .flatpickr-months .flatpickr-month {
            background-color: #0d6efd;
            /* Warna biru untuk header bulan */
            color: white;
            border-radius: 5px;
        }

        .flatpickr-current-month .flatpickr-monthDropdown-months,
        .flatpickr-current-month .numInputWrapper {
            color: black;
        }

        .flatpickr-calendar .flatpickr-day.selected,
        .flatpickr-calendar .flatpickr-day.startRange,
        .flatpickr-calendar .flatpickr-day.endRange,
        .flatpickr-calendar .flatpickr-day.selected.inRange,
        .flatpickr-calendar .flatpickr-day.startRange.inRange,
        .flatpickr-calendar .flatpickr-day.endRange.inRange,
        .flatpickr-calendar .flatpickr-day.selected:focus,
        .flatpickr-calendar .flatpickr-day.startRange:focus,
        .flatpickr-calendar .flatpickr-day.endRange:focus,
        .flatpickr-calendar .flatpickr-day.selected:hover,
        .flatpickr-calendar .flatpickr-day.startRange:hover,
        .flatpickr-calendar .flatpickr-day.endRange:hover,
        .flatpickr-calendar .flatpickr-day.selected.prevMonth,
        .flatpickr-calendar .flatpickr-day.selected.nextMonth,
        .flatpickr-calendar .flatpickr-day.startRange.prevMonth,
        .flatpickr-calendar .flatpickr-day.startRange.nextMonth,
        .flatpickr-calendar .flatpickr-day.endRange.prevMonth,
        .flatpickr-calendar .flatpickr-day.endRange.nextMonth {
            background: #0d6efd !important;
            /* Warna biru untuk tanggal yang dipilih */
            border-color: #0d6efd !important;
            color: #fff;
        }

        .flatpickr-calendar .flatpickr-day.inRange {
            background: #e6f2ff !important;
            /* Warna biru muda untuk rentang tanggal */
            border-color: #e6f2ff !important;
            color: #0d6efd;
            /* Warna teks biru tua */
        }

        .flatpickr-calendar .flatpickr-day.today.selected {
            background: #0d6efd !important;
            border-color: #0d6efd !important;
            color: #fff;
        }

        /* Styling untuk tanggal yang dinonaktifkan (disabled) bawaan Flatpickr */
        /* Ini masih akan digunakan untuk tanggal yang sudah ada di keranjang atau sudah dipilih di UI */
        .flatpickr-calendar .flatpickr-day.flatpickr-disabled,
        .flatpickr-calendar .flatpickr-day.flatpickr-disabled:hover {
            color: #b0b0b0 !important;
            background-color: #e9ecef !important; /* Keep background light grey for truly disabled dates */
            cursor: not-allowed;
            opacity: 0.7;
        }

        /* NEW STYLE: Custom class for past dates (gray text, transparent background) */
        .flatpickr-calendar .flatpickr-day.past-date-disabled {
            color: #b0b0b0 !important; /* Gray text color */
            background-color: transparent !important; /* Transparent background */
            cursor: not-allowed;
            opacity: 1; /* Keep full opacity if desired, or adjust */
            pointer-events: none; /* Make it unclickable */
        }

        /* Pastikan hover state tidak mempengaruhi past-date-disabled */
        .flatpickr-calendar .flatpickr-day.past-date-disabled:hover {
            background-color: transparent !important;
            color: #b0b0b0 !important;
        }


        /* Navigasi panah Flatpickr */
        .flatpickr-months .flatpickr-prev-month,
        .flatpickr-months .flatpickr-next-month {
            fill: white;
        }

        /* Style untuk weekdays */
        .flatpickr-weekdays {
            background-color: #f8f9fa;
            /* Warna latar belakang untuk baris hari */
            border-top-left-radius: 5px;
            border-top-right-radius: 5px;
        }

        .flatpickr-weekday {
            color: #343a40;
            /* Warna teks untuk nama hari */
            font-weight: bold;
        }

        /* Hover effect for days */
        .flatpickr-day:hover {
            background: #007bff;
            /* Example hover color */
            color: #fff;
        }

        /* New styles for thumbnails */
        .thumbnail-item {
            cursor: pointer;
            border: 2px solid transparent;
            transition: border-color 0.2s ease-in-out;
        }

        .thumbnail-item.active-thumbnail {
            border-color: #0d6efd;
            /* Bootstrap primary blue */
            padding: 2px;
        }

        /* --- Custom Modal Width --- */
        /* Akan diterapkan hanya pada breakpoint md (768px) ke atas */
        @media (min-width: 768px) {
            .modal-dialog-custom-width {
                max-width: 800px;
                /* Lebar maksimum yang lebih 'pas' */
            }

            /* Tambahkan jarak kustom di antara kolom */
            .modal-body.row.g-2>[class*="col-"]:not(:last-child) {
                padding-right: 10px;
                /* Setengah dari 20px yang diinginkan */
            }

            .modal-body.row.g-2>[class*="col-"]:last-child {
                padding-left: 10px;
                /* Setengah dari 20px yang diinginkan */
            }
        }


        /* --- Responsive CSS using Media Queries --- */

        /* Desktop styles (maintain current layout) */
        .main-content {
            display: flex;
            gap: 15px;
        }

        .thumbnail-container {
            width: 11%;
        }

        .carousel-section {
            flex: 2;
        }

        .product-details-section {
            flex: 1;
        }

        .detail-card {
            height: 430px;
            /* Maintain desktop height */
        }

        .review-hr {
            width: 1100px;
            /* Maintain desktop width for HR in reviews */
        }

        .user-avatar {
            width: 90px;
            height: 90px;
        }

        /* Mobile styles (for iPhone 12 Pro and similar smaller screens) */
        @media (max-width: 768px) {
            .main-content {
                flex-direction: column;
                /* Stack elements vertically */
                gap: 0;
                /* Remove gap when stacked */
            }

            .thumbnail-container {
                width: 100%;
                /* Full width for thumbnails */
                display: flex;
                /* Make thumbnails horizontal scrollable */
                overflow-x: auto;
                white-space: nowrap;
                padding-bottom: 10px;
                /* Add some padding for scrollbar */
                order: 2;
                /* Move thumbnails below the main carousel */
            }

            .thumbnail-item {
                flex: 0 0 auto;
                /* Prevent items from shrinking */
                margin-bottom: 15px;
                width: 80px;
                /* Adjust thumbnail size for mobile */
                height: 80px;
                margin-right: 10px;
                /* Space between thumbnails */
            }

            .thumbnail-item img {
                height: 100% !important;
                /* Ensure image fills container */

            }

            .carousel-section {
                flex: none;
                /* Remove flex property */
                width: 100%;
                /* Full width for carousel */
                order: 1;
                /* Place carousel at the top */
            }

            .product-details-section {
                flex: none;
                /* Remove flex property */
                width: 100%;
                /* Full width for product details */
                order: 3;
                /* Place product details below thumbnails */
                margin-top: 15px;
                /* Add some space */
            }

            .detail-card {
                height: auto;
                /* Allow height to adjust based on content */
                max-height: none;
                /* Remove max-height constraint */
            }

            .price-section h1 {
                font-size: 1.5rem !important;
                /* Adjust font size for mobile */
            }

            .price-section span {
                font-size: 0.8rem !important;
                /* Adjust font size for mobile */
            }

            .modal-dialog {
                margin: 0.5rem;
                /* Adjust modal margin for smaller screens */
            }

            .modal-body.row {
                flex-direction: column;
                /* Stack modal body content */
            }

            .modal-body .col-12.col-md-6 {
                /* Selector lama yang masih digunakan untuk mobile */
                width: 100%;
                /* Full width for columns in modal */
                /* Pastikan padding kembali normal untuk mobile */
                padding-left: var(--bs-gutter-x, 0.75rem) !important;
                padding-right: var(--bs-gutter-x, 0.75rem) !important;
            }

            .modal-body .col-12.text-center {
                /* Untuk small text di bawah modal */
                padding-left: var(--bs-gutter-x, 0.75rem);
                padding-right: var(--bs-gutter-x, 0.75rem);
            }

            .add-to-cart-section {
                flex-direction: column;
                /* Stack add to cart button */
                align-items: center;
                /* Center the button */
            }

            .add-to-cart-section hr {
                display: none;
                /* Hide HR line on mobile */
            }

            .add-to-cart-section form {
                width: 100%;
                /* Make button full width */
            }

            .user-review-heading {
                text-align: center;
                /* Center review heading */
                padding-left: 0 !important;
                padding-right: 0 !important;
                margin-top: 20px;
            }

            .review-card {
                margin-left: 10px;
                margin-right: 10px;
            }

            .review-card .card-body {
                flex-direction: column;
                /* Stack review elements vertically */
                align-items: center;
                /* Center items in review card */
            }

            .user-avatar-container {
                width: 100%;
                /* Full width for avatar container */
                justify-content: center !important;
                /* Center avatar */
                margin-bottom: 10px;
            }

            .user-avatar {
                width: 70px;
                /* Smaller avatar for mobile */
                height: 70px;
            }

            .user-review-content {
                width: 100%;
                /* Full width for review content */
                padding-left: 0 !important;
                /* Remove left padding */
                text-align: center;
                /* Center review text */
            }

            .review-hr {
                width: 80% !important;
                /* Adjust HR width for mobile */
                margin-left: auto;
                margin-right: auto;
            }
        }


        /* ===*/

        /* Styling untuk dropdown bulan */
        .flatpickr-current-month select.flatpickr-monthDropdown-months {
            appearance: none;
            /* Menghilangkan default arrow pada select */
            background-color: #0d6efd;
            /* Warna latar belakang sama dengan header bulan */
            color: white;
            /* Warna teks putih */
            border: 1px solid #0d6efd;
            /* Border tipis */
            border-radius: 4px;
            /* Sedikit pembulatan sudut */
            padding: 2px 5px;
            /* Padding agar tidak terlalu mepet */
            font-weight: 450;
            cursor: pointer;
            /* Tambahkan icon custom jika appearance: none digunakan */
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3E%3Cpath fill='white' d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 5px top 50%;
            background-size: 0.65em auto;
        }

        /* Hover dan focus state untuk dropdown bulan */
        .flatpickr-current-month select.flatpickr-monthDropdown-months:hover,
        .flatpickr-current-month select.flatpickr-monthDropdown-months:focus {
            background-color: #0a58ca;
            /* Warna sedikit lebih gelap saat hover/focus */
            border-color: #0a58ca;
            outline: none !important;
            /* Hilangkan outline default focus */
        }
    </style>

</x-layout>