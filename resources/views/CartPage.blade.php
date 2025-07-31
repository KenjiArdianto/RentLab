<x-layout>

    <div class="container my-4">

        <div class="row">
            <div class="col-12 cart-header-wrapper mb-3">
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ __(session('error')) }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="d-flex bg-light border rounded me-0 p-3 ps-0 pe-0 cart-header shadow-sm">

                    <div class="col-1 d-none d-md-flex justify-content-center align-items-center">

                    </div>
                    <div class="d-flex col-12 col-md-11 ps-md-4 header-content-wrapper">
                        <div
                            class="col-6 col-md-2 d-flex align-items-center justify-content-center justify-content-md-start">
                            <p class="fw-bold m-0 text-center text-md-start">{{__('cart.Product')}}</p>
                        </div>
                        <div class="col-md-4 d-none d-md-block">

                        </div>
                        <div class="col-3 d-none d-md-flex align-items-center">
                            <p class="fw-bold m-0">{{__('cart.OrderDate')}}</p>
                        </div>
                        <div class="col-3 d-none d-md-flex align-items-center">
                            <p class="fw-bold m-0">{{__('cart.TotalPrice')}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        @if ($upcomingCart->isNotEmpty())
            <h4 class="mb-3">{{__('cart.RecentDate')}}</h4>
            @foreach ($upcomingCart as $cart)
                <x-CartCardComponent :param="$cart" :isOutdated="false" />
                <x-CartResponsive :param="$cart" :isOutdated="false" />
            @endforeach
        @endif


        @if ($outdatedCart->isNotEmpty())
            <div class="container d-flex justify-content-between px-0">
                <h4 class="mt-5 mb-4">{{__('cart.PastDate')}}</h4>

                <form class="mt-5 mb-4 justify-content-end" action="{{ route('cart.clearOutdated') }}" method="POST"
                    onsubmit="return confirm('{{__('cart.DeleteWarning')}}');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-warning btn-sm">
                        <i class="bi bi-trash"></i> {{__('cart.DeleteExpiredButton')}}
                    </button>
                </form>
            </div>
            @foreach ($outdatedCart as $cart)
                <x-CartCardComponent :param="$cart" :isOutdated="true" />
                <x-CartResponsive :param="$cart" :isOutdated="true" />
            @endforeach
        @endif

        @if ($upcomingCart->isEmpty() && $outdatedCart->isEmpty())
            <div class="text-center py-5">
                <p class="lead">{{__('cart.CartEmpty')}}</p>
                <a href="{{ route('vehicle.display') }}" class="btn btn-primary">{{__('cart.AddItem')}}</a>
            </div>
        @endif

    </div>

    <div class="container my-4 sticky-bottom">

        <div class="row cart-footer-row">
            <div class="col-12 p-0">
                <div class="bg-light d-flex align-items-center cart-footer shadow-sm rounded">

                    <h1 class="col-1 d-none d-md-block"></h1>
                    <div class="d-lg-block d-none col-12 col-md-6 footer-text-col">
                        <h5 class="mb-0 fs-5">Total (<span id="total-product-desktop">0</span> {{__('cart.Product')}}) :
                        </h5>
                        <h6 class="text-muted ">{{__('cart.PaymentInfo')}}</h6>
                    </div>
                    <h3 class="d-lg-block d-none col-12 col-md-3">Rp.<span id="total-price-desktop">0</span>,00</h3>

                    <div
                        class="d-lg-none d-block col-12 col-md-6 d-flex justify-content-md-start align-items-center footer-price-summary">
                        <h5 class="mb-0 fs-6 me-2 text-start">Total (<span id="total-product-mobile">0</span>
                            {{__('cart.Product')}}) : </h5>
                        <h3 class="mb-0">Rp.<span id="total-price-mobile">0</span>,00</h3>
                    </div>
                    <h6 class="d-lg-none d-block text-muted text-start w-100 mt-1">{{__('cart.PaymentInfo')}}</h6>

                    <div class="col-12 col-md-2 d-flex justify-content-center justify-items-center pe-md-3">

                        <form id="paymentForm" action="{{ route('checkout.show') }}" method="POST"
                            style="width: 90%; height: 40px;">
                            @csrf
                            <div id="cartIdsContainer"></div>
                            <button type="submit" class="btn btn-primary my-3 my-md-0"
                                style="height: 100%; width: 100%;">
                                {{__('cart.PaymentButton')}}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const breakpointLg = 992;

            //sinii
            function calculateAndUpdateSubtotal(checkbox) {
                // const vehiclePrice = parseFloat(checkbox.dataset.vehiclePrice);
                // const startDateStr = checkbox.dataset.startDate;
                // const endDateStr = checkbox.dataset.endDate;
                const subtotal = parseFloat(checkbox.dataset.subtotal);

                // if (isNaN(vehiclePrice) || !startDateStr || !endDateStr) {
                //     console.error("Data harga atau tanggal tidak lengkap untuk perhitungan subtotal.");
                //     return;
                // }

                if (isNaN(subtotal)) {
                    console.error("Data subtotal tidak lengkap untuk perhitungan.");
                    return;
                }

                // const startDate = moment(startDateStr);
                // const endDate = moment(endDateStr);
                // const numberOfDays = endDate.diff(startDate, 'days') + 1;

                // const subtotal = vehiclePrice * numberOfDays;

                // const parentItem = checkbox.closest('.cart-item-container-desktop') || checkbox.closest('.cart-responsive-item');
                // if (parentItem) {
                //     const subtotalDisplayElement = parentItem.querySelector('.subtotal-display');
                //     if (subtotalDisplayElement) {
                //         subtotalDisplayElement.innerText = `Rp.${subtotal.toLocaleString('id-ID')},00`;
                //     }
                // }

                const parentItem = checkbox.closest('.cart-item-container-desktop') || checkbox.closest('.cart-responsive-item');
                if (parentItem) {
                    const subtotalDisplayElement = parentItem.querySelector('.subtotal-display');
                    if (subtotalDisplayElement) {
                        subtotalDisplayElement.innerText = `Rp.${subtotal.toLocaleString('id-ID')},00`;
                    }
                }

                checkbox.dataset.price = subtotal;
            }

            document.querySelectorAll('.cart-checkbox').forEach(cb => {
                calculateAndUpdateSubtotal(cb);
            });
            //sini

            //--
            // function calculateAndUpdateSubtotal(checkbox) {
            //     // Ensure data-subtotal is present and is a valid number
            //     const subtotal = parseFloat(checkbox.dataset.subtotal);

            //     if (isNaN(subtotal)) {
            //         console.error("Data subtotal is missing or invalid for calculation.", checkbox);
            //         // Set to 0 or handle error appropriately if subtotal is not available
            //         checkbox.dataset.price = 0;
            //         return;
            //     }

            //     // Find the parent element to update the displayed subtotal
            //     const parentItem = checkbox.closest('.cart-item-container-desktop') || checkbox.closest('.cart-responsive-item');
            //     if (parentItem) {
            //         const subtotalDisplayElement = parentItem.querySelector('.subtotal-display');
            //         if (subtotalDisplayElement) {
            //             subtotalDisplayElement.innerText = `Rp.${subtotal.toLocaleString('id-ID')},00`;
            //         }
            //     }

            //     // Crucially, set the data-price for the checkbox, which updateCartSummary will read
            //     checkbox.dataset.price = subtotal;
            // }

            // // Initialize subtotals for all cart items on page load
            // document.querySelectorAll('.cart-checkbox').forEach(cb => {
            //     calculateAndUpdateSubtotal(cb);
            // });
            //--

            function synchronizeCheckboxes() {
                const desktopCheckboxes = document.querySelectorAll('.desktop-checkbox');
                const mobileCheckboxes = document.querySelectorAll('.mobile-checkbox');

                desktopCheckboxes.forEach((desktopCb, index) => {
                    if (mobileCheckboxes[index]) {

                        if (!desktopCb.disabled) {
                            if (window.innerWidth >= breakpointLg) {
                                mobileCheckboxes[index].checked = desktopCb.checked;
                            } else {
                                desktopCb.checked = mobileCheckboxes[index].checked;
                            }
                        }
                    }
                });
            }

            function updateCartSummary() {
                let total = 0;
                let count = 0;
                let selectedIds = [];


                let visibleCheckboxes;
                if (window.innerWidth >= breakpointLg) {
                    visibleCheckboxes = document.querySelectorAll('.desktop-checkbox:not([disabled])');
                } else {
                    visibleCheckboxes = document.querySelectorAll('.mobile-checkbox:not([disabled])');
                }

                visibleCheckboxes.forEach(cb => {
                    if (cb.checked) {
                        const itemPrice = parseFloat(cb.dataset.price);
                        const cartId = cb.dataset.cartId;
                        if (!isNaN(itemPrice)) {
                            total += itemPrice;
                            count++;
                            selectedIds.push(cartId);
                        }
                    }
                });


                const totalPriceDesktopEl = document.getElementById('total-price-desktop');
                const totalProductDesktopEl = document.getElementById('total-product-desktop');
                if (totalPriceDesktopEl && totalProductDesktopEl) {
                    totalPriceDesktopEl.innerText = total.toLocaleString('id-ID');
                    totalProductDesktopEl.innerText = count;
                }


                const totalPriceMobileEl = document.getElementById('total-price-mobile');
                const totalProductMobileEl = document.getElementById('total-product-mobile');
                if (totalPriceMobileEl && totalProductMobileEl) {
                    totalPriceMobileEl.innerText = total.toLocaleString('id-ID');
                    totalProductMobileEl.innerText = count;
                }


                const container = document.getElementById('cartIdsContainer');
                // Kosongkan ID lama setiap kali ada perubahan
                container.innerHTML = '';

                // Buat input baru untuk setiap ID yang dipilih
                selectedIds.forEach(id => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'cart_ids[]'; // <-- Nama ini penting agar terbaca sebagai array di Laravel
                    input.value = id;
                    container.appendChild(input);
                });
            }


            document.querySelectorAll('.cart-checkbox').forEach(cb => {
                cb.addEventListener('change', function () {
                    synchronizeCheckboxes();
                    updateCartSummary();
                });
            });


            window.addEventListener('resize', function () {
                synchronizeCheckboxes();
                updateCartSummary();
            });


            synchronizeCheckboxes();
            updateCartSummary();
            const paymentForm = document.getElementById('paymentForm');

            if (paymentForm) {
                paymentForm.addEventListener('submit', function(event) {
                    // Temukan tombol submit di dalam form
                    const submitButton = paymentForm.querySelector('button[type="submit"]');

                    if (submitButton) {
                        // Nonaktifkan tombol
                        submitButton.disabled = true;

                        // Ubah teks dan tambahkan ikon spinner
                        submitButton.innerHTML = `
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Memproses...
                        `;
                    }
                });
            }
        });
    </script>


    <style>
        img.img-fluid {
            max-width: 100%;
            height: auto;
            display: block;

        }


        .cart-header {
            height: 70px;
            margin-left: 4px;
            margin-right: 12px;
            box-shadow: 0 .125rem .25rem rgba(0, 0, 0, .075) !important;
        }

        .cart-footer {
            height: 70px;
            padding-left: 1rem;
            padding-right: 1rem;
            margin-top: 20px;
        }

        .cart-footer .btn {
            width: 60%;
            margin: 0 12px;
        }


        .cart-footer .btn a {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            width: 100%;
        }


        .cart-item-outdated {
            opacity: 0.6;
            pointer-events: none;
        }

        .cart-item-outdated .delete-button {
            pointer-events: auto !important;
            opacity: 1 !important;
        }

        .cart-item-outdated .cart-checkbox {
            pointer-events: none;
        }


        @media (max-width: 991.98px) {

            .cart-header-wrapper,
            .cart-footer-row {
                padding-left: 0 !important;
                padding-right: 0 !important;
            }

            .cart-header {
                flex-direction: column;
                height: auto;
                padding: 10px !important;
                margin: 0 !important;
            }

            .cart-header .header-content-wrapper {
                flex-direction: column;
                padding-left: 0 !important;
                align-items: center;
            }

            .cart-header .col-6.col-md-2 {
                width: 100%;
                text-align: center;
                margin-bottom: 5px;
            }


            .cart-header .col-3 {
                display: none !important;
            }


            .cart-header .col-1 {
                display: none !important;
            }

            .cart-footer-row {
                position: static !important;
                margin-top: 20px !important;
            }

            .cart-footer {
                flex-direction: column;
                height: auto;
                padding: 10px !important;
                margin-left: 0 !important;
                margin-right: 0 !important;
                align-items: center;
                text-align: center;
            }

            .cart-footer h5,
            .cart-footer h3 {
                width: 100%;
                text-align: center;
                margin-bottom: 5px;
            }

            .cart-footer h6.text-muted {
                font-size: 0.8rem;
                margin-top: 0;
                margin-bottom: 10px;
            }

            .cart-footer .footer-price-summary {
                flex-direction: row;
                justify-content: center;
                width: 100%;
                margin-bottom: 5px;
            }

            .cart-footer .col-12.col-md-2 {
                width: 100%;
                padding-left: 0 !important;
                padding-right: 0 !important;
            }

            .cart-footer .btn {
                width: 90%;
                margin: 0 auto;
            }


            .cart-responsive-item {
                height: 130px;
            }
        }
    </style>




</x-layout>
