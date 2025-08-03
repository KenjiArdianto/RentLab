<x-layout>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

    <div class="px-0 px-lg-5 mx-0 mx-lg-5">
        <div class="main-content mt-4">
            {{-- Thumbnails --}}
            <div class="thumbnail-container">
                <div class="h-20 thumbnail-item active-thumbnail" data-bs-slide-to="0">
                    <img src="{{ $idVehicle->main_image }}" class="w-100 shadow-sm rounded-1"
                        alt="Main Product Image 1">
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
                        <h1 class="mb-1 fw-bold">{{$idVehicle->vehicleName->name}} {{$idVehicle->year}}</h1>

                        {{-- vehicle address --}}
                        <div class="d-flex justify-content-between align-items-center p-0">
                            <div class="d-flex justify-content-center align-items-center">
                                <i class="bi bi-geo-alt-fill me-2"></i>
                                <h4 class="text-muted m-0">{{$idVehicle->location->location}}</h4>
                            </div>

                            {{-- vehicle rating--}}
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

                    {{-- Harga kendaraan --}}
                    <div class="price-section">
                        <h1 class="mb-0 fs-3">Rp.{{ number_format($idVehicle->price, 0, ',', '.')}},00<span
                                class="fs-6 text-muted"> /{{__('vehicle.PerDay')}}</span></h1>
                    </div>

                    {{-- vehicle description  --}}
                    <div class="container">
                        <div class="row row-cols-2">
                            <p class="my-2 detail-item col d-flex mx-0 p-0 ">
                                <i class="bi bi-person-fill mx-1 pt-1"></i>
                                <span> {{$idVehicle->seats}} {{__('vehicle.Seats')}}</span>
                            </p>

                            <div class="d-flex justify-content-center align-items-center p-0">
                                <img class="mt-1 me-2" src="{{asset('images/Pedal.png')}}" alt="pedal"
                                    style="width: 15px; height: 15px; ">
                                <p class="my-2 detail-item col p-0">{{$idVehicle->vehicleTransmission->transmission}}
                                </p>
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

                    {{-- add date  --}}
                    <div class="container overflow-auto card-container border border-2 rounded-2"
                        style="height: 174px;">
                        <h5 class="mt-1 mb-2">{{__('vehicle.AddDate')}}</h5>    
                        <div class="input-group mb-2">
                            <span class="input-group-text" id="calendarIcon" style="cursor: pointer;"
                                aria-label="Open Calendar">
                                <i class="bi bi-calendar-plus"></i>
                            </span>
                            <input type="text" class="form-control" id="dateInput"
                                placeholder="{{__('vehicle.DateRange')}}" aria-label="Selected date range" disabled>
                        </div>

                        <hr class="mt-3 mb-2">

                        <h5 class="mb-2">{{__('vehicle.SelectedDate')}}</h5>
                        <div id="cartDisplay">
                            <p id="noDatesMessage" class="text-muted">{{__('vehicle.DatesSelected')}}</p>
                        </div>
                    </div>

                    {{-- select date pop up --}}
                    <div class="modal fade" id="datePickerModal" tabindex="-1" aria-labelledby="datePickerModalLabel"
                        aria-hidden="true">

                        <div class="modal-dialog modal-dialog-centered modal-dialog-custom-width">
                            <div class="modal-content" style="width:700px">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="datePickerModalLabel">{{__('vehicle.Header')}}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>

                                <div class="modal-body row g-2">
                                    {{-- Calender Input --}}
                                    <div class="col-12 col-md-6 text-center d-flex pe-0 justify-content-center">
                                        <div id="dateRangePicker"></div>
                                    </div>

                                    {{-- Show user Cart on vehicle --}}
                                    <div class="col-12 col-md-6 ps-0">
                                        <h6 class="text-center mt-3 mt-md-0">{{__('vehicle.Content')}}</h6>
                                        <div class="overflow-auto container p-2" style="max-height: 250px;">
                                            @if ($getVehicleByIdsINCarts)
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
                                                                <form
                                                                    action="{{route('cart.destroy', ['id' => $getVehicleByIdsINCart->id])}}"
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
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 text-center">
                                        <small
                                            class="text-muted mt-3 d-block">{{__('vehicle.FirstInformation')}}</small>
                                        <small class="text-muted d-block">{{__('vehicle.SecondInformation')}}</small>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">{{__('vehicle.Close')}}</button>
                                    <button type="button" class="btn btn-primary"
                                        id="saveDatesBtn">{{__('vehicle.Save')}}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
 
        {{-- add selected date to cart--}}
        <div class="container mb-3 mt-2 d-flex justify-content-between align-items-start px-0 add-to-cart-section">
            <hr class="flex-grow-1 ms-0 me-3 mb-0">
            <form class="mb-0" id="addToCartForm" method="POST" action="{{route('cart.store')}}">
                @csrf
                <input type="hidden" name="vehicle_id" value="{{ $idVehicle->id }}">
                <div id="hiddenDateInputs"></div>
                <button class="btn btn-secondary bg-primary" type="submit"
                    id="addToCartBtn">{{__('vehicle.ButtonAdd')}}</button>
            </form>
        </div>

        <h2 class="p-2 user-review-heading">{{__('vehicle.UserReviewHeader')}}</h2>

        @if ($getCommentByIdVehicle)
            {{-- Show user review --}}
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
        @endif

        
    </div>


    <script>
        const cartDateRangesFromDB = @json($cartDateRanges);
        const bookedDatesFromDB = @json($allBookedDates); 
        const MAX_CART_ITEMS = 10; 
    </script>

    <script>
        $(document).ready(function () {
            const MAX_DATE_RANGES = 3;
            let selectedDateRanges = [];
            let flatpickrInstance;
            const dateInput = $('#dateInput');
            const cartDisplay = $('#cartDisplay');
            const datePickerModalInstance = new bootstrap.Modal(document.getElementById('datePickerModal'));


            async function getCartItemCount() {
                try {
                    const response = await fetch("{{ route('cart.itemCount') }}");
                    const data = await response.json();
                    return data.count;
                } catch (error) {
                    console.error('Error fetching cart item count:', error);
                    return 0;
                }
            }

            flatpickrInstance = flatpickr("#dateRangePicker", {
                mode: "range",
                inline: true,
                dateFormat: "Y-m-d",
                minDate: "today",
                
                disable: [
                    function (date) {
                        let isBooked = false;
                        bookedDatesFromDB.forEach(range => {
                            const bookedStart = moment(range.start_date).startOf('day');
                            const bookedEnd = moment(range.end_date).endOf('day');
                            if (moment(date).isBetween(bookedStart, bookedEnd, null, '[]')) {
                                isBooked = true;
                            }
                        });

                        if (!isBooked) { 
                        cartDateRangesFromDB.forEach(range => {
                            const cartStart = moment(range.start_date).startOf('day');
                            const cartEnd = moment(range.end_date).endOf('day');
                            if (moment(date).isBetween(cartStart, cartEnd, null, '[]')) {
                                isBooked = true;
                            }
                        });
                    }

                        return isBooked;
                    }
                ],

                onDayCreate: function (dObj, dStr, fp, dayElem) {
                    const date = new Date(dayElem.dateObj.getFullYear(), dayElem.dateObj.getMonth(), dayElem.dateObj.getDate());
                    const today = new Date();
                    const normalizedToday = new Date(today.getFullYear(), today.getMonth(), today.getDate());


                    if (date < normalizedToday) {
                        dayElem.classList.add('past-date-disabled');
                        dayElem.setAttribute('title', 'Tanggal sudah lewat');
                    }

                    for (const range of selectedDateRanges) {
                        const rangeStart = new Date(range.startDate.getFullYear(), range.startDate.getMonth(), range.startDate.getDate());
                        const rangeEnd = new Date(range.endDate.getFullYear(), range.endDate.getMonth(), range.endDate.getDate());

                        if (date >= rangeStart && date <= rangeEnd) {
                            dayElem.classList.add('flatpickr-disabled');
                            dayElem.setAttribute('title', 'Sudah dipilih (saat ini)');
                            break;
                        }
                    }


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

                dateInput.val('No date range selected');
                flatpickrInstance.clear();
                flatpickrInstance.redraw();
                datePickerModalInstance.show();
            });

            $('#saveDatesBtn').on('click', async function () {
                const selectedDates = flatpickrInstance.selectedDates;

                if (selectedDates.length === 0) {
                    alert('Harap pilih rentang tanggal.');
                    return;
                }

                //maximal select 3 dates
                if (selectedDateRanges.length >= MAX_DATE_RANGES) {
                    alert(`Anda hanya dapat memilih maksimal 3 rentang tanggal.`);
                    return;
                }

                const currentCartCount = await getCartItemCount();
                const newItemsCount = 1;
                const potentialTotal = currentCartCount + newItemsCount + selectedDateRanges.length;

                if (potentialTotal > MAX_CART_ITEMS) {
                    alert(`{{__('vehicle.PageWarning10Items')}}`);
                    return;
                }

                let startDate = selectedDates[0];
                let endDate = (selectedDates.length === 2) ? selectedDates[1] : selectedDates[0];


                if (startDate > endDate) {
                    [startDate, endDate] = [endDate, startDate];
                }


                const newStart = new Date(startDate.getFullYear(), startDate.getMonth(), startDate.getDate());
                const newEnd = new Date(endDate.getFullYear(), endDate.getMonth(), endDate.getDate());


                const isOverlapping = selectedDateRanges.some(existingRange => {
                    const existingStart = new Date(existingRange.startDate.getFullYear(), existingRange.startDate.getMonth(), existingRange.startDate.getDate());
                    const existingEnd = new Date(existingRange.endDate.getFullYear(), existingRange.endDate.getMonth(), existingRange.endDate.getDate());
                    return (newStart <= existingEnd && newEnd >= existingStart);
                });


                const isOverlappingWithDB = cartDateRangesFromDB.some(dbRange => {
                    const dbStart = new Date(moment(dbRange.start_date).format('YYYY-MM-DD'));
                    const dbEnd = new Date(moment(dbRange.end_date).format('YYYY-MM-DD'));
                    return (newStart <= dbEnd && newEnd >= dbStart);
                });


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

                selectedDateRanges.sort((a, b) => a.startDate - b.startDate); 

                datePickerModalInstance.hide();
                updateCartDisplay();
                flatpickrInstance.redraw();

            });

            $('#addToCartBtn').on('click', function () {
                if (selectedDateRanges.length === 0) {
                    alert("{{ __('vehicle.WarningCart') }}");
                    return false;
                }
            });

            function updateCartDisplay() {
                cartDisplay.empty();
               
                if (selectedDateRanges.length === 0) {
                    cartDisplay.append('<p id="noDatesMessage" class="text-muted">{{__('vehicle.DateRange')}}</p>');
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
                        flatpickrInstance.redraw();
                    });
                }
            }
            updateCartDisplay();

            $('#addToCartForm').on('submit', function (e) {

                e.preventDefault(); 


                const hiddenInputContainer = $('#hiddenDateInputs');
                hiddenInputContainer.empty();

                if (selectedDateRanges.length === 0) {
                    alert("Belum ada tanggal yang dipilih.");
                    return;
                }

                const addToCartBtn = $('#addToCartBtn');
                addToCartBtn.prop('disabled', true).text('Adding...'); 

                selectedDateRanges.forEach((range, index) => {
                    const start = moment(range.startDate).format('YYYY-MM-DD');
                    const end = moment(range.endDate).format('YYYY-MM-DD');

                    hiddenInputContainer.append(`
                        <input type="hidden" name="date_ranges[${index}][start_date]" value="${start}">
                        <input type="hidden" name="date_ranges[${index}][end_date]" value="${end}">
                    `);
                });

                this.submit(); 
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
        .flatpickr-calendar {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;

            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);

            border-radius: 8px;

            padding: 10px;
        }


        .flatpickr-calendar.inline {
            width: 100%;

            max-width: max-content;

            margin: auto;

        }

        .flatpickr-months .flatpickr-month {
            background-color: #0d6efd;

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

            border-color: #0d6efd !important;
            color: #fff;
        }

        .flatpickr-calendar .flatpickr-day.inRange {
            background: #e6f2ff !important;

            border-color: #e6f2ff !important;
            color: #0d6efd;

        }

        .flatpickr-calendar .flatpickr-day.today.selected {
            background: #0d6efd !important;
            border-color: #0d6efd !important;
            color: #fff;
        }


        .flatpickr-calendar .flatpickr-day.flatpickr-disabled,
        .flatpickr-calendar .flatpickr-day.flatpickr-disabled:hover {
            color: #b0b0b0 !important;
            background-color: #e9ecef !important;
            cursor: not-allowed;
            opacity: 0.7;
        }


        .flatpickr-calendar .flatpickr-day.past-date-disabled {
            color: #b0b0b0 !important;
            background-color: transparent !important;
            cursor: not-allowed;
            opacity: 1;
            pointer-events: none;
        }


        .flatpickr-calendar .flatpickr-day.past-date-disabled:hover {
            background-color: transparent !important;
            color: #b0b0b0 !important;
        }



        .flatpickr-months .flatpickr-prev-month,
        .flatpickr-months .flatpickr-next-month {
            fill: white;
        }


        .flatpickr-weekdays {
            background-color: #f8f9fa;

            border-top-left-radius: 5px;
            border-top-right-radius: 5px;
        }

        .flatpickr-weekday {
            color: #343a40;

            font-weight: bold;
        }


        .flatpickr-day:hover {
            background: #007bff;

            color: #fff;
        }


        .thumbnail-item {
            cursor: pointer;
            border: 2px solid transparent;
            transition: border-color 0.2s ease-in-out;
        }

        .thumbnail-item.active-thumbnail {
            border-color: #0d6efd;

            padding: 2px;
        }


        @media (min-width: 768px) {
            .modal-dialog-custom-width {
                max-width: 800px;

            }


            .modal-body.row.g-2>[class*="col-"]:not(:last-child) {
                padding-right: 10px;

            }

            .modal-body.row.g-2>[class*="col-"]:last-child {
                padding-left: 10px;

            }
        }





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

        }

        .review-hr {
            width: 1100px;

        }

        .user-avatar {
            width: 90px;
            height: 90px;
        }


        @media (max-width: 768px) {
            .main-content {
                flex-direction: column;

                gap: 0;

            }

            .thumbnail-container {
                width: 100%;

                display: flex;

                overflow-x: auto;
                white-space: nowrap;
                padding-bottom: 10px;

                order: 2;

            }

            .thumbnail-item {
                flex: 0 0 auto;

                margin-bottom: 15px;
                width: 80px;

                height: 80px;
                margin-right: 10px;

            }

            .thumbnail-item img {
                height: 100% !important;

            }

            .carousel-section {
                flex: none;

                width: 100%;

                order: 1;

            }

            .product-details-section {
                flex: none;

                width: 100%;

                order: 3;

                margin-top: 15px;

            }

            .detail-card {
                height: auto;

                max-height: none;

            }

            .price-section h1 {
                font-size: 1.5rem !important;

            }

            .price-section span {
                font-size: 0.8rem !important;

            }

            .modal-dialog {
                margin: 0.5rem;

            }

            .modal-body.row {
                flex-direction: column;

            }

            .modal-body .col-12.col-md-6 {

                width: 100%;

                padding-left: var(--bs-gutter-x, 0.75rem) !important;
                padding-right: var(--bs-gutter-x, 0.75rem) !important;
            }

            .modal-body .col-12.text-center {

                padding-left: var(--bs-gutter-x, 0.75rem);
                padding-right: var(--bs-gutter-x, 0.75rem);
            }

            .add-to-cart-section {
                flex-direction: column;

                align-items: center;

            }

            .add-to-cart-section hr {
                display: none;

            }

            .add-to-cart-section form {
                width: 100%;

            }

            .user-review-heading {
                text-align: center;

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

                align-items: center;

            }

            .user-avatar-container {
                width: 100%;

                justify-content: center !important;

                margin-bottom: 10px;
            }

            .user-avatar {
                width: 70px;

                height: 70px;
            }

            .user-review-content {
                width: 100%;

                padding-left: 0 !important;

                text-align: center;

            }

            .review-hr {
                width: 80% !important;

                margin-left: auto;
                margin-right: auto;
            }
        }



        .flatpickr-current-month select.flatpickr-monthDropdown-months {
            appearance: none;

            background-color: #0d6efd;

            color: white;

            border: 1px solid #0d6efd;

            border-radius: 4px;

            padding: 2px 5px;

            font-weight: 450;
            cursor: pointer;

            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3E%3Cpath fill='white' d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 5px top 50%;
            background-size: 0.65em auto;
        }


        .flatpickr-current-month select.flatpickr-monthDropdown-months:hover,
        .flatpickr-current-month select.flatpickr-monthDropdown-months:focus {
            background-color: #0a58ca;

            border-color: #0a58ca;
            outline: none !important;

        }
    </style>

</x-layout>