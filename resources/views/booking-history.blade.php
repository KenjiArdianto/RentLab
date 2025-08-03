<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Booking History</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="{{ asset('booking_history_assets/css/booking-history.css') }}">
</head>
<x-layout>
    <div class="container my-3">
        <div class="transaction-controls p-3 rounded-4 bg-white shadow-sm">
            @php
                $activeTab = request('active_tab', 'ongoing');
                $isHistoryActive = $activeTab == 'history';
                $sliderLeft = $isHistoryActive ? '50%' : '2px';
                $sliderWidth = 'calc(50% - 4px)';
            @endphp
            <nav class="nav nav-pills nav-fill mb-4" id="transaction-toggle" style="--slider-left: {{ $sliderLeft }}; --slider-width: {{ $sliderWidth }};">
                <a class="nav-link {{ !$isHistoryActive ? 'active' : '' }}" href="#ongoing-content" data-bs-toggle="tab" data-tab="ongoing">{{ __('booking-history.on_going') }}</a>
                <a class="nav-link {{ $isHistoryActive ? 'active' : '' }}" href="#history-content" data-bs-toggle="tab" data-tab="history">{{ __('booking-history.history') }}</a>
            </nav>
            <form action="{{ route('booking.history') }}" method="GET">
                <input type="hidden" name="active_tab" value="{{ $activeTab }}">
                <div class="search-container">
                    <div class="search-segment flex-grow-1">
                        <input type="text" name="history_search" class="form-control" placeholder="{{ __('booking-history.search_vehicle') }}" value="{{ request('history_search') }}">
                    </div>
                    <div class="search-divider"></div>
                    <div class="search-segment">
                        <input type="text" name="date_from" class="form-control date-picker" placeholder="{{ __('booking-history.start_date') }}" value="{{ request('date_from') }}">
                    </div>
                    <div class="search-divider"></div>
                    <div class="search-segment">
                        <input type="text" name="date_to" class="form-control date-picker" placeholder="{{ __('booking-history.end_date') }}" value="{{ request('date_to') }}">
                    </div>
                    <button type="submit" class="btn btn-primary px-4 ms-2">{{ __('booking-history.search_btn') }}</button>
                </div>
            </form>
        </div>

        <div class="tab-content mt-4" id="myTabContent">
            {{-- ================= TAB ON-GOING ================= --}}
            <div class="tab-pane fade {{ !$isHistoryActive ? 'show active' : '' }}" id="ongoing-content" role="tabpanel">
                @forelse ($ongoingItems as $item)
                    @if ($item instanceof \Illuminate\Support\Collection)
                        @php $paymentGroup = $item; @endphp
                        <div class="card shadow-sm mb-3">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Invoice ID: {{ $paymentGroup->first()->payment->external_id }}</h6>
                                <span class="badge rounded-pill text-bg-warning">{{ __('booking-history.status.on_payment') }}</span>
                            </div>
                            <div class="card-body">
                                @foreach ($paymentGroup as $transaction)
                                    <div class="row g-3 align-items-center mb-3 {{ !$loop->last ? 'border-bottom pb-3' : '' }}">
                                        <div class="col-md-2">
                                            <img src="{{ $transaction->vehicle?->main_image ?? 'https://via.placeholder.com/150' }}" class="img-fluid rounded" alt="Vehicle Image">
                                        </div>
                                        <div class="col-md-5">
                                            <p class="text-muted mb-1 small">{{ \Carbon\Carbon::parse($transaction->start_book_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($transaction->end_book_date)->format('d M Y') }}</p>
                                            <h5 class="card-title mb-1">{{ $transaction->vehicle?->vehicleName?->name ?? 'N/A' }} ({{ $transaction->vehicle?->year }})</h5>
                                            <p class="card-text">{{ $transaction->vehicle?->vehicleType?->type ?? '-' }} / {{ $transaction->vehicle?->vehicleTransmission?->transmission ?? '-' }}</p>
                                        </div>
                                        <div class="col-md-5 text-md-end">
                                            <p class="text-muted mb-1 small">Subtotal Item</p>
                                            <h5 class="mb-3">Rp {{ number_format($transaction->price ?? 0, 0, ',', '.') }}</h5>
                                            <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#detailModal{{ $transaction->id }}">
                                                {{ __('booking-history.button.view_detail') }}
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                                <div class="text-end mt-3 border-top pt-3">
                                    <p class="mb-1">Total Pembayaran</p>
                                    <h4 class="fw-bold text-danger">Rp{{ number_format($paymentGroup->first()->payment->amount, 0, ',', '.') }}</h4>
                                     <a href="{{ $paymentGroup->first()->payment->url }}" target="_blank" class="btn btn-sm btn-danger mt-2">
                                        {{ __('payment.buttons.pay_now') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        @foreach ($paymentGroup as $transaction)
                             @include('partials.booking-transaction-detail', ['transaction' => $transaction])
                        @endforeach
                    @else
                        @php $transaction = $item; @endphp
                         <div class="card shadow-sm mb-3">
                            <div class="card-body">
                                <div class="row g-3 align-items-center">
                                    <div class="col-md-2"><img src="{{ $transaction->vehicle?->main_image ?? 'https://via.placeholder.com/150' }}" class="img-fluid rounded"></div>
                                    <div class="col-md-5">
                                        <p class="text-muted mb-1 small">{{ \Carbon\Carbon::parse($transaction->start_book_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($transaction->end_book_date)->format('d M Y') }}</p>
                                        <h5 class="card-title mb-1">{{ $transaction->vehicle?->vehicleName?->name ?? 'N/A' }} ({{ $transaction->vehicle?->year }})</h5>
                                        <p class="card-text">{{ $transaction->vehicle?->vehicleType?->type ?? '-' }} / {{ $transaction->vehicle?->vehicleTransmission?->transmission ?? '-' }}</p>
                                    </div>
                                    <div class="col-md-5 text-md-end">
                                        @php
                                            $statusConfig = [2 => ['text' => __('booking-history.status.on_booking'), 'bg' => 'text-bg-info'], 3 => ['text' => __('booking-history.status.vehicle_taken'), 'bg' => 'text-bg-primary']];
                                            $currentStatus = $statusConfig[$transaction->transaction_status_id] ?? ['text' => 'Unknown', 'bg' => 'text-bg-dark'];
                                        @endphp
                                        <span class="badge rounded-pill {{ $currentStatus['bg'] }} mb-2">{{ $currentStatus['text'] }}</span>
                                        
                                        @php
                                            $vehiclePrice = $transaction->price;
                                            
                                            $driverFee = ($transaction->driver_price > 0 || $transaction->driver_id) ? ($transaction->driver_price > 0 ? $transaction->driver_price : 50000) : 0;
                                            
                                            $thisItemTotal = $vehiclePrice + $driverFee;
                                        @endphp

                                        <p class="text-muted mb-1 small">Total Harga</p>
                                        <h5 class="mb-3">Rp {{ number_format($thisItemTotal, 0, ',', '.') }}</h5>
                                        
                                        <div>
                                            <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#detailModal{{ $transaction->id }}">
                                                {{ __('booking-history.button.view_detail') }}
                                            </button>
                                            <a href="{{ route('receipt.download', ['payment' => $transaction->payment_id]) }}" class="btn btn-primary btn-sm rounded-pill px-3 ms-2">
                                                {{ __('booking-history.button.download_receipt') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                         </div>
                        @include('partials.booking-transaction-detail', ['transaction' => $transaction])
                    @endif
                @empty
                    <div class="text-center p-5 bg-light rounded"><p class="text-muted"> {{ __('booking-history.modal.noresult') }}</p></div>
                @endforelse
                <div class="mt-4 d-flex justify-content-center">
                    {{ $ongoingItems->appends(['active_tab' => 'ongoing'] + request()->except(['ongoingPage', 'active_tab']))->links() }}
                </div>
            </div>

            {{-- ================= TAB HISTORY ================= --}}
            <div class="tab-pane fade {{ $isHistoryActive ? 'show active' : '' }}" id="history-content" role="tabpanel">
                @forelse ($historyTransactions as $transaction)
                    <div class="card shadow-sm mb-3">
                         <div class="card-body">
                            <div class="row g-3 align-items-center">
                                <div class="col-md-2">
                                    <img src="{{ $transaction->vehicle?->main_image ?? 'https://via.placeholder.com/150' }}" class="img-fluid rounded" alt="Vehicle Image">
                                </div>
                                <div class="col-md-5">
                                    <p class="text-muted mb-1 small">{{ \Carbon\Carbon::parse($transaction->start_book_date)->translatedFormat('d M Y') }} - {{ \Carbon\Carbon::parse($transaction->end_book_date)->translatedFormat('d M Y') }}</p>
                                    <h5 class="card-title mb-1">{{ $transaction->vehicle?->vehicleName?->name ?? 'N/A' }} ({{ $transaction->vehicle?->year }})</h5>
                                    <p class="card-text">{{ $transaction->vehicle?->vehicleType?->type ?? '-' }} / {{ $transaction->vehicle?->vehicleTransmission?->transmission ?? '-' }}</p>
                                </div>
                                <div class="col-md-5 text-md-end">
                                    <div class="mb-2">
                                        @if(in_array($transaction->transaction_status_id, [4, 5]))
                                            @if($transaction->userReview->isEmpty())
                                                <span class="badge rounded-pill text-bg-secondary me-1">{{ __('booking-history.status.admin_review_pending') }}</span>
                                            @endif
                                            @if(!$transaction->vehicleReview)
                                                <span class="badge rounded-pill text-bg-warning text-dark">{{ __('booking-history.status.awaiting_review') }}</span>
                                            @endif
                                        @elseif($transaction->transaction_status_id == 6)
                                            <span class="badge rounded-pill text-bg-success">{{ __('booking-history.status.reviewed_closed') }}</span>
                                        @elseif($transaction->transaction_status_id == 7)
                                            <span class="badge rounded-pill text-bg-danger">{{ __('booking-history.status.canceled') }}</span>
                                        @endif
                                    </div>

                                    @php
                                        $vehiclePrice = $transaction->vehicle_price;
                                        
                                        $driverFee = ($transaction->driver_price > 0 || $transaction->driver_id) ? ($transaction->driver_price > 0 ? $transaction->driver_price : 50000) : 0;
                                        
                                        $thisItemTotal = $vehiclePrice + $driverFee;
                                    @endphp

                                    <p class="text-muted mb-1 small mt-1">Total Harga</p>
                                    <h5 class="mb-3">Rp {{ number_format($thisItemTotal, 0, ',', '.') }}</h5>

                                    <div>
                                        <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#detailModal{{ $transaction->id }}">
                                            @if (!$transaction->vehicleReview && in_array($transaction->transaction_status_id, [4, 5]))
                                                {{ __('booking-history.button.leave_review') }}
                                            @else
                                                {{ __('booking-history.button.view_detail') }}
                                            @endif
                                        </button>
                                        @if($transaction->transaction_status_id != 7)
                                        <a href="{{ route('receipt.download', ['payment' => $transaction->payment_id]) }}" class="btn btn-primary btn-sm rounded-pill px-3 ms-2">
                                            {{ __('booking-history.button.download_receipt') }}
                                        </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                     @include('partials.booking-transaction-detail', ['transaction' => $transaction])
                @empty
                    <div class="text-center p-5 bg-light rounded"><p class="text-muted">{{ __('booking-history.modal.noresulttransaction') }}</p></div>
                @endforelse
                <div class="mt-4 d-flex justify-content-center">
                    {{ $historyTransactions->appends(['active_tab' => 'history'] + request()->except(['historyPage', 'active_tab']))->links() }}
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="{{ asset('booking_history_assets/js/booking-history.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Script untuk membuka modal jika ada error validasi dari server
            @if ($errors->any() && session('open_modal'))
                var modalId = '#detailModal' + {{ session('open_modal') }};
                var errorModal = document.querySelector(modalId);
                if (errorModal) {
                    new bootstrap.Modal(errorModal).show();
                }
            @endif

            // Script untuk menjaga state tab aktif saat form filter disubmit
            const tabToggle = document.querySelector('#transaction-toggle');
            const activeTabInput = document.querySelector('input[name="active_tab"]');

            if (tabToggle && activeTabInput) {
                tabToggle.addEventListener('click', function(event) {
                    if (event.target.matches('.nav-link[data-tab]')) {
                        const tabName = event.target.getAttribute('data-tab');
                        activeTabInput.value = tabName;
                    }
                });
            }
        });
    </script>
</x-layout>