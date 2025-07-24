<x-layout>

    <div class="container my-4">
        {{-- Header/Navbar for Cart --}}
        <div class="row">
            <div class="col-12 cart-header-wrapper mb-3">
                <div class="d-flex bg-light border rounded me-0 p-3 ps-0 pe-0 cart-header shadow-sm">
                    {{-- Hidden on small screens, shown on medium-up --}}
                    <div class="col-1 d-none d-md-flex justify-content-center align-items-center">
                        {{-- Empty div for alignment on desktop --}}
                    </div>
                    <div class="d-flex col-12 col-md-11 ps-md-4 header-content-wrapper">
                        <div class="col-6 col-md-2 d-flex align-items-center justify-content-center justify-content-md-start">
                            <p class="fw-bold m-0 text-center text-md-start">Produk</p>
                        </div>
                        <div class="col-md-4 d-none d-md-block">
                            {{-- Empty div for spacing on desktop --}}
                        </div>
                        <div class="col-3 d-none d-md-flex align-items-center">
                            <p class="fw-bold m-0">Tanggal Pesanan</p>
                        </div>
                        <div class="col-3 d-none d-md-flex align-items-center">
                            <p class="fw-bold m-0">Total Harga</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Cart Items --}}
        @foreach ($listCart as $cart)
            <x-CartCardComponent :param="$cart" />
            <x-CartResponsive :param="$cart" />
        @endforeach

    </div>

    <div class="container m-0 sticky-bottom">
        {{-- Footer - Total Price and Payment Button --}}
        <div class="row  cart-footer-row">
            <div class="col-12 p-0">
                <div class="bg-light d-flex align-items-center cart-footer shadow-sm rounded">
                    {{-- Hidden on mobile --}}  
                    <h1 class="col-1 d-none d-md-block"></h1>
                    <div class="d-lg-block d-none col-12 col-md-6 footer-text-col">
                        <h5 class="mb-0 fs-5">Total (<span id="total-product-desktop">0</span> Produk) : </h5>
                        <h6 class="text-muted ">Pembayaran dapat dilanjutkan dengan menekan Payment</h6>
                    </div>
                    <h3 class="d-lg-block d-none col-12 col-md-3">Rp.<span id="total-price-desktop">0</span>,00</h3>

                    <div class="d-lg-none d-block col-12 col-md-6 d-flex justify-content-md-start align-items-center footer-price-summary">
                        <h5 class="mb-0 fs-6 me-2  text-start">Total (<span id="total-product-mobile">0</span> Produk) : </h5>
                        <h3 class="mb-0">Rp.<span id="total-price-mobile">0</span>,00</h3>
                    </div>
                    <h6 class="d-lg-none d-block text-muted text-start w-100 mt-1">Pembayaran dapat dilanjutkan dengan menekan Payment</h6>

                    <div class="col-12 col-md-2 d-flex justify-content-center pe-md-3">
                        <button type="button" class="btn btn-primary my-3 my-md-0" style="height: 40px; width: 90%;">
                            <a href="" class="text-light text-decoration-none d-block h-100 d-flex align-items-center justify-content-center">Payment</a>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    

    {{-- JavaScript for Cart Summary --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const breakpointLg = 992; // Bootstrap's 'lg' breakpoint

            // Function to synchronize checkbox states between desktop and mobile components
            function synchronizeCheckboxes() {
                const desktopCheckboxes = document.querySelectorAll('.desktop-checkbox');
                const mobileCheckboxes = document.querySelectorAll('.mobile-checkbox');

                desktopCheckboxes.forEach((desktopCb, index) => {
                    if (mobileCheckboxes[index]) {
                        // If desktop is visible, copy desktop state to mobile
                        if (window.innerWidth >= breakpointLg) {
                            mobileCheckboxes[index].checked = desktopCb.checked;
                        } else {
                            // If mobile is visible, copy mobile state to desktop
                            desktopCb.checked = mobileCheckboxes[index].checked;
                        }
                    }
                });
            }

            function updateCartSummary() {
                let total = 0;
                let count = 0;

                // Select only the currently visible checkboxes for calculation
                let visibleCheckboxes;
                if (window.innerWidth >= breakpointLg) {
                    visibleCheckboxes = document.querySelectorAll('.desktop-checkbox');
                } else {
                    visibleCheckboxes = document.querySelectorAll('.mobile-checkbox');
                }

                visibleCheckboxes.forEach(cb => {
                    if (cb.checked) {
                        total += parseInt(cb.dataset.price);
                        count++;
                    }
                });

                // Update for desktop display
                const totalPriceDesktopEl = document.getElementById('total-price-desktop');
                const totalProductDesktopEl = document.getElementById('total-product-desktop');
                if (totalPriceDesktopEl && totalProductDesktopEl) {
                    totalPriceDesktopEl.innerText = total.toLocaleString('id-ID');
                    totalProductDesktopEl.innerText = count;
                }

                // Update for mobile display
                const totalPriceMobileEl = document.getElementById('total-price-mobile');
                const totalProductMobileEl = document.getElementById('total-product-mobile');
                if (totalPriceMobileEl && totalProductMobileEl) {
                    totalPriceMobileEl.innerText = total.toLocaleString('id-ID');
                    totalProductMobileEl.innerText = count;
                }
            }

            // Add event listeners to ALL checkboxes (both desktop and mobile ones)
            // Any change should trigger a re-calculation and state synchronization
            document.querySelectorAll('.cart-checkbox').forEach(cb => {
                cb.addEventListener('change', function() {
                    synchronizeCheckboxes(); // Sync other component's checkbox
                    updateCartSummary();    // Recalculate based on currently visible
                });
            });

            // Recalculate and synchronize when the window is resized
            window.addEventListener('resize', function() {
                synchronizeCheckboxes(); // Sync checkbox states on resize
                updateCartSummary();    // Then update summary based on current view
            });

            // Initial synchronization and calculation on page load
            synchronizeCheckboxes();
            updateCartSummary();
        });
    </script>

    {{-- Custom CSS for Responsiveness --}}
    <style>
        /* General styles for images */
        img.img-fluid {
            max-width: 100%;
            height: auto;
            display: block; /* Ensures no extra space below the image */
        }

        /* Desktop styles - default layout */
        .cart-header {
            height: 70px;
            margin-left: 4px;
            margin-right: 12px;
            box-shadow: 0 .125rem .25rem rgba(0,0,0,.075)!important; /* Added shadow for consistency */
        }

        .cart-footer {
            height: 70px;
            padding-left: 1rem;
            padding-right: 1rem;
            margin-top: 20px; /* Adjusted margin to ensure it doesn't stick too close */
        }

        .cart-footer .btn {
            width: 60%;
            margin: 0 12px;
        }

        /* Specific styles for the "Payment" button's anchor tag */
        .cart-footer .btn a {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            width: 100%;
        }

        /* Mobile styles (max-width: 991.98px for Bootstrap's 'lg' breakpoint) */
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

            /* Hide "Tanggal Pesanan" and "Total Harga" columns on mobile header */
            .cart-header .col-3 {
                display: none !important;
            }

            /* Hide the empty column that was for desktop alignment */
            .cart-header .col-1 {
                display: none !important;
            }

            .cart-footer-row {
                position: static !important; /* Remove sticky behavior on mobile */
                margin-top: 20px !important;
            }

            .cart-footer {
                flex-direction: column;
                height: auto;
                padding: 10px !important;
                margin-left: 0 !important;
                margin-right: 0 !important;
                align-items: center; /* Center content vertically */
                text-align: center;
            }

            .cart-footer h5, .cart-footer h3 {
                width: 100%; /* Full width for text elements */
                text-align: center; /* Center text */
                margin-bottom: 5px; /* Add space between elements */
            }

            .cart-footer h6.text-muted {
                font-size: 0.8rem; /* Smaller font for mobile hint text */
                margin-top: 0;
                margin-bottom: 10px;
            }

            .cart-footer .footer-price-summary {
                flex-direction: row; /* Keep total and product count in one row */
                justify-content: center; /* Center them */
                width: 100%;
                margin-bottom: 5px;
            }

            .cart-footer .col-12.col-md-2 {
                width: 100%; /* Full width for button container */
                padding-left: 0 !important;
                padding-right: 0 !important;
            }

            .cart-footer .btn {
                width: 90%; /* Make button almost full width on mobile */
                margin: 0 auto; /* Center button horizontally */
            }

            /* Styles specific to mobile card component */
            .cart-responsive-item {
                height: 130px; /* Keep consistent height as per original */
            }
        }
    </style>

</x-layout>