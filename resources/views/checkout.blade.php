<x-layout>
    <div class="container my-5">
        <form id="finalPaymentForm" action="{{ route('payment.process') }}" method="POST">
            @csrf

            <div class="row g-5">
                {{-- KOLOM KIRI: Detail Tagihan & Item --}}
                <div class="col-md-7 col-lg-8">
                    <h4 class="mb-3">{{ __('checkout.billing_details') }}</h4>
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="name" class="form-label">{{ __('checkout.full_name') }}</label>
                                    <input type="text" class="form-control" id="name" value="{{ $user->name ?? '' }}" readonly>
                                </div>

                                <div class="col-12">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" value="{{ $user->email ?? '' }}" readonly>
                                </div>

                            </div>
                        </div>
                    </div>

                    <h4 class="mb-3">{{ __('checkout.items_to_checkout') }}</h4>
                    <div class="list-group">
                        @forelse ($cartItems as $item)
                            <div class="list-group-item list-group-item-action mb-3 border rounded shadow-sm">
                                <div class="row align-items-center">
                                    <div class="col-3 col-lg-2">
                                        {{-- Ganti dengan path gambar yang benar --}}
                                        <img src="{{ $item->vehicle->main_image }}" class="img-fluid rounded" alt="{{ $item->vehicle->vehicleName->name }}">
                                    </div>
                                    <div class="col-9 col-lg-10">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h5 class="mb-1">{{ $item->vehicle->vehicleName->name }}</h5>
                                            <span class="fw-bold fs-5 text-nowrap">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</span>
                                        </div>
                                        <p class="mb-1 text-muted">{{ $item->vehicle->vehicleType->type }} | {{ $item->vehicle->vehicleTransmission->transmission }}</p>
                                        <small>{{ \Carbon\Carbon::parse($item->start_date)->isoFormat('D MMM YYYY') }} &rarr; {{ \Carbon\Carbon::parse($item->end_date)->isoFormat('D MMM YYYY') }}</small>
                                    </div>
                                </div>
                            </div>
                            {{-- Input tersembunyi untuk setiap item yang akan di-checkout --}}
                            <input type="hidden" name="cart_ids[]" value="{{ $item->id }}">
                        @empty
                            <div class="alert alert-warning" role="alert">
                                {{ __('checkout.no_items') }}
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- KOLOM KANAN: Ringkasan Pesanan (Sticky di Desktop) --}}
                <div class="col-md-5 col-lg-4">
                    <div class="position-sticky" style="top: 2rem;">
                        <div class="card shadow-sm">
                            <div class="card-header py-3">
                                <h4 class="my-0 fw-normal">{{ __('checkout.summary') }}</h4>
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        {{ __('checkout.subtotal') }} ({{ $cartItems->count() }} {{ __('checkout.items') }})
                                        <span>Rp{{ number_format($totalAmount, 0, ',', '.') }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        {{ __('checkout.service_fee') }}
                                        <span>Rp0</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent border-top fw-bold">
                                        <span>Total</span>
                                        <span class="fs-5">Rp{{ number_format($totalAmount, 0, ',', '.') }}</span>
                                    </li>
                                </ul>

                                <button type="submit" class="btn btn-primary btn-lg w-100 mt-3">
                                    <i class="bi bi-shield-check-fill me-2"></i>
                                    {{ __('checkout.pay_now') }}
                                </button>
                                <small class="d-block text-center text-muted mt-2">
                                    <i class="bi bi-lock-fill"></i> {{ __('checkout.secure_payment') }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- Script untuk menampilkan spinner pada tombol saat form disubmit --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const paymentForm = document.getElementById('finalPaymentForm');
            if (paymentForm) {
                paymentForm.addEventListener('submit', function (event) {
                    const submitButton = paymentForm.querySelector('button[type="submit"]');
                    if (submitButton) {
                        submitButton.disabled = true;
                        submitButton.innerHTML = `
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            {{ __('checkout.processing') }}...
                        `;
                    }
                });
            }
        });
    </script>
</x-layout>
