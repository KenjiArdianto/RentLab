<x-layout>
    @php
        // Ambil ID item yang tidak tersedia dari session flash data, defaultnya array kosong
        $unavailableCartIds = session('unavailable_cart_ids', []);
    @endphp

    <style>
        /* CSS untuk item yang tidak tersedia */
        .item-unavailable {
            opacity: 0.6;
            background-color: #f8f9fa; /* Sedikit abu-abu untuk membedakan */
            border-left: 4px solid #dc3545; /* Tambahkan garis merah di kiri sebagai indikator */
        }
        .item-unavailable .form-check-input,
        .item-unavailable .form-check-label {
            pointer-events: none; /* Mencegah klik pada checkbox sopir */
        }
    </style>

    <div class="container my-5">
        {{-- Tampilkan pesan error global jika ada --}}
        @if (session('error'))
            <div class="alert alert-danger" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                {{ session('error') }}
            </div>
        @endif

        <form id="finalPaymentForm" action="{{ route('payment.process') }}" method="POST">
            @csrf

            <div class="row g-5">
                {{-- KOLOM KIRI: Detail Tagihan & Item --}}
                <div class="col-md-7 col-lg-8">
                    <h4 class="mb-3">{{ __('checkout.billing_details') }}</h4>
                    <div class="card shadow-sm mb-4">
                        {{-- ... Konten detail tagihan tidak berubah ... --}}
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="name" class="form-label">{{ __('checkout.full_name') }}</label>
                                    <input type="text" class="form-control" id="name" value="{{ Auth::user()->detail->fname.' '.Auth::user()->detail->lname }}" readonly>
                                </div>
                                <div class="col-12">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" value="{{ $user->email }}" readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h4 class="mb-3">{{ __('checkout.items_to_checkout') }}</h4>
                    <div class="list-group">
                        @forelse ($cartItems as $item)
                            @php
                                $isUnavailable = in_array($item->id, $unavailableCartIds);
                                $startDate = \Carbon\Carbon::parse($item->start_date);
                                $endDate = \Carbon\Carbon::parse($item->end_date);
                                $days = $startDate->diffInDays($endDate) + 1;
                                $driverFee = 50000 * $days;
                            @endphp

                            {{-- Tambahkan class 'item-unavailable' jika item sudah dipesan --}}
                            <div class="list-group-item list-group-item-action mb-3 border rounded shadow-sm @if($isUnavailable) item-unavailable @endif">
                                <div class="row align-items-center">
                                    <div class="col-3 col-lg-2">
                                        <img src="{{ $item->vehicle->main_image }}" class="img-fluid rounded" alt="{{ $item->vehicle->vehicleName->name }}">
                                    </div>
                                    <div class="col-9 col-lg-10">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h5 class="mb-1">{{ $item->vehicle->vehicleName->name }}</h5>
                                            <span class="fw-bold fs-5 text-nowrap">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</span>
                                        </div>

                                        {{-- PERUBAHAN: Tampilkan pesan error spesifik di sini --}}
                                        @if($isUnavailable)
                                            <div class="text-danger fw-bold my-1">
                                                <small><i class="bi bi-x-circle-fill"></i> Item ini tidak lagi tersedia pada tanggal yang dipilih.</small>
                                            </div>
                                        @endif

                                        <p class="mb-1 text-muted">{{ $item->vehicle->vehicleType->type }} | {{ $item->vehicle->vehicleTransmission->transmission }}</p>
                                        <small>{{ $startDate->isoFormat('D MMM YYYY') }} &rarr; {{ $endDate->isoFormat('D MMM YYYY') }}</small>

                                        <div class="form-check mt-2">
                                            <input class="form-check-input driver-checkbox"
                                                   type="checkbox"
                                                   name="with_driver[{{ $item->id }}]"
                                                   value="1"
                                                   id="driver_{{ $item->id }}"
                                                   data-fee="{{ $driverFee }}"
                                                   {{-- Nonaktifkan checkbox jika item tidak tersedia --}}
                                                   @if($isUnavailable) disabled @endif
                                                   {{-- Pertahankan status checked jika sebelumnya dipilih --}}
                                                   @if(old('with_driver.'.$item->id)) checked @endif>
                                            <label class="form-check-label" for="driver_{{ $item->id }}">
                                                Tambah Sopir (+Rp{{ number_format($driverFee, 0, ',', '.') }})
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- Tetap kirim semua ID yang awalnya dipilih agar halaman bisa dirender ulang dengan benar --}}
                            <input type="hidden" name="cart_ids[]" value="{{ $item->id }}">
                        @empty
                            <div class="alert alert-warning" role="alert">
                                {{ __('checkout.no_items') }}
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- KOLOM KANAN: Ringkasan Pesanan --}}
                <div class="col-md-5 col-lg-4">
                    <div class="position-sticky" style="top: 2rem;">
                        <div class="card shadow-sm">
                            <div class="card-header py-3">
                                <h4 class="my-0 fw-normal">{{ __('checkout.summary') }}</h4>
                            </div>
                            <div class="card-body">
                                {{-- ... Konten ringkasan tidak berubah ... --}}
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <span>{{ __('checkout.subtotal') }} ({{ $cartItems->count() }} {{ __('checkout.items') }})</span>
                                        <span>Rp{{ number_format($totalAmount, 0, ',', '.') }}</span>
                                    </li>
                                    <li id="driver-fee-line" class="list-group-item d-flex justify-content-between align-items-center px-0" style="display: none;">
                                        <span>Biaya Sopir</span>
                                        <span id="summary-driver-fee">Rp0</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <span>{{ __('checkout.service_fee') }}</span>
                                        <span>Rp0</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent border-top fw-bold">
                                        <span>Total</span>
                                        <span class="fs-5" id="summary-total">Rp{{ number_format($totalAmount, 0, ',', '.') }}</span>
                                    </li>
                                </ul>

                                {{-- Nonaktifkan tombol bayar jika ada item yang tidak tersedia --}}
                                <button type="submit" class="btn btn-primary btn-lg w-100 mt-3" @if(!empty($unavailableCartIds)) disabled @endif>
                                    <i class="bi bi-shield-check-fill me-2"></i>
                                    {{ __('checkout.pay_now') }}
                                </button>

                                @if(!empty($unavailableCartIds))
                                    <small class="d-block text-center text-danger mt-2 fw-bold">
                                        Harap kembali ke <a href="{{ route('cart') }}">keranjang</a> untuk memperbarui pesanan Anda.
                                    </small>
                                @else
                                    <small class="d-block text-center text-muted mt-2">
                                        <i class="bi bi-lock-fill"></i> {{ __('checkout.secure_payment') }}
                                    </small>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- Script JS tidak perlu diubah, karena sudah menangani checkbox yang dinonaktifkan --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const baseTotal = @json($totalAmount);
            const summaryTotalEl = document.getElementById('summary-total');
            const driverFeeLineEl = document.getElementById('driver-fee-line');
            const summaryDriverFeeEl = document.getElementById('summary-driver-fee');
            const driverCheckboxes = document.querySelectorAll('.driver-checkbox');

            function formatCurrency(number) {
                return 'Rp' + new Intl.NumberFormat('id-ID').format(number);
            }

            function updateTotalPrice() {
                let totalDriverFee = 0;
                driverCheckboxes.forEach(checkbox => {
                    if (checkbox.checked) { // Checkbox yang disabled tidak akan pernah 'checked'
                        totalDriverFee += parseFloat(checkbox.dataset.fee);
                    }
                });

                if (totalDriverFee > 0) {
                    summaryDriverFeeEl.textContent = formatCurrency(totalDriverFee);
                    driverFeeLineEl.style.display = 'flex';
                } else {
                    driverFeeLineEl.style.display = 'none';
                }

                const grandTotal = baseTotal + totalDriverFee;
                summaryTotalEl.textContent = formatCurrency(grandTotal);
            }

            driverCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateTotalPrice);
            });

            // Panggil sekali saat halaman dimuat untuk sinkronisasi awal
            updateTotalPrice();

            const paymentForm = document.getElementById('finalPaymentForm');
            if (paymentForm) {
                const processingText = @json(__('checkout.processing'));
                paymentForm.addEventListener('submit', function (event) {
                    const submitButton = paymentForm.querySelector('button[type="submit"]');
                    if (submitButton) {
                        submitButton.disabled = true;
                        submitButton.innerHTML = `
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            ${processingText}...
                        `;
                    }
                });
            }
        });
    </script>
</x-layout>
