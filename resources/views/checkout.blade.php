<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@lang('payment.title.checkout') - RentLab</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Menambahkan ikon Bootstrap untuk spinner --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-7">

                <h2 class="mb-4 text-center">@lang('payment.title.checkout')</h2>

                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                {{-- FIX: Menambahkan ID pada form untuk referensi JavaScript --}}
                <form id="paymentForm" action="{{ route('payment.process', ['locale' => app()->getLocale()]) }}" method="POST">
                    @csrf

                    @if($cartItems->isNotEmpty())
                        {{-- Kirim kembali ID keranjang yang dipilih --}}
                        @foreach($selectedCartIds as $id)
                            <input type="hidden" name="cart_ids[]" value="{{ $id }}">
                        @endforeach

                        <!-- Order Summary Card -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">@lang('payment.summary.heading')</h5>
                            </div>
                            <ul class="list-group list-group-flush">
                                {{-- Loop melalui setiap item di keranjang --}}
                                @foreach($cartItems as $item)
                                    <li class="list-group-item d-flex justify-content-between">
                                        <div>
                                            <h6 class="my-0">{{ $item->vehicle->vehicleName->name }}</h6>
                                            <small class="text-muted">
                                                {{ $item->duration }} hari &times; Rp {{ number_format($item->vehicle->price) }}
                                            </small>
                                        </div>
                                        <span class="text-muted">Rp {{ number_format($item->subtotal) }}</span>
                                    </li>
                                @endforeach

                                <li class="list-group-item d-flex justify-content-between bg-light">
                                    <strong class="fs-5">@lang('payment.summary.total')</strong>
                                    <strong class="fs-5 text-primary">Rp {{ number_format($totalAmount) }}</strong>
                                </li>
                            </ul>
                        </div>

                        <div class="alert alert-info small">
                            Anda akan diarahkan ke halaman pembayaran yang aman milik Xendit untuk memilih metode pembayaran (Virtual Account, QRIS, dll).
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                @lang('payment.buttons.pay_now')
                            </button>
                        </div>
                    @else
                        <div class="alert alert-warning text-center">
                            Keranjang Anda kosong.
                        </div>
                    @endif
                </form>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- =================================================================== --}}
    {{-- FIX: Menambahkan JavaScript untuk mencegah double submission        --}}
    {{-- =================================================================== --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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
</body>
</html>
