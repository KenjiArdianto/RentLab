@php
    $currLang = session()->get('lang', 'en');
    app()->setLocale($currLang);
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking History</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="{{ asset('build/assets/css/booking-history.css') }}">
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

            <nav class="nav nav-pills nav-fill mb-4" id="transaction-toggle" 
                 style="--slider-left: {{ $sliderLeft }}; --slider-width: {{ $sliderWidth }};">
                
                <a class="nav-link {{ !$isHistoryActive ? 'active' : '' }}" href="#ongoing-content" data-bs-toggle="tab" data-tab="ongoing">{{ __('booking-history.on_going') }}</a>
                <a class="nav-link {{ $isHistoryActive ? 'active' : '' }}" href="#history-content" data-bs-toggle="tab" data-tab="history">{{ __('booking-history.history') }}</a>
            </nav>
            
            <form action="{{ route('booking.history') }}" method="GET">
                <input type="hidden" name="active_tab" value="{{ $activeTab }}">
                <div class="d-flex align-items-center p-1 form-control border-primary rounded-4">
                    <div class="row g-2 align-items-center w-100">
                        <div class="col-md-5">
                            <input type="text" name="search" class="form-control" placeholder="{{ __('booking-history.search_vehicle') }}" value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="date_from" class="form-control date-picker" placeholder="{{ __('booking-history.date_placeholder') }}" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="date_to" class="form-control date-picker" placeholder="{{ __('booking-history.date_placeholder') }}" value="{{ request('date_to') }}">
                        </div>
                        <div class="col-md-1">
                            <button type="submit" class="btn btn-primary w-100">{{ __('booking-history.search_btn') }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="tab-content mt-4" id="myTabContent">
            {{-- ================= TAB ON-GOING ================= --}}
            <div class="tab-pane fade {{ !$isHistoryActive ? 'show active' : '' }}" id="ongoing-content" role="tabpanel">
                @forelse ($ongoingTransactions as $transaction)
                    <div class="card shadow-sm mb-3">
                        <div class="card-body">
                            <div class="row g-3 align-items-center">
                                <div class="col-md-2">
                                    <img src="{{ $transaction->vehicle?->main_image ?? 'https://via.placeholder.com/150' }}" class="img-fluid rounded" alt="Vehicle Image">
                                </div>
                                <div class="col-md-5">
                                    <p class="text-muted mb-1 small">{{ \Carbon\Carbon::parse($transaction->start_book_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($transaction->end_book_date)->format('d M Y') }}</p>
                                    <h5 class="card-title mb-1">{{ $transaction->vehicle?->vehicleName?->name ?? 'N/A' }} ({{ $transaction->vehicle?->year }})</h5>
                                    <p class="card-text">{{ $transaction->vehicle?->vehicleType?->type ?? '-' }} / {{ $transaction->vehicle?->vehicleTransmission?->transmission ?? '-' }}</p>
                                </div>
                                <div class="col-md-5 text-md-end">
                                    @php
                                        $statusConfig = [
                                            1 => ['text' => __('booking-history.status.on_payment'), 'bg' => 'text-bg-warning'],
                                            2 => ['text' => __('booking-history.status.on_booking'), 'bg' => 'text-bg-info'],
                                            3 => ['text' => __('booking-history.status.vehicle_taken'), 'bg' => 'text-bg-primary'],
                                        ];
                                        $currentStatus = $statusConfig[$transaction->transaction_status_id] ?? ['text' => 'Unknown', 'bg' => 'text-bg-dark'];
                                    @endphp
                                    <span class="badge rounded-pill {{ $currentStatus['bg'] }} mb-2">{{ $currentStatus['text'] }}</span>
                                    <p class="text-muted mb-1 small">{{ __('booking-history.label.total_price') }}</p>
                                    <h5 class="mb-3">Rp {{ number_format($transaction->total_price ?? 0, 0, ',', '.') }}</h5>
                                    <div>
                                        <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#detailModal{{ $transaction->id }}">
                                            {{ __('booking-history.button.view_detail') }}
                                        </button>
                                        <a href="{{ route('receipt.download', $transaction) }}" class="btn btn-primary btn-sm rounded-pill px-3 ms-2">
                                            {{ __('booking-history.button.download_receipt') }}
                                        </a>
                                    </div>  
                                </div>
                            </div>
                        </div>
                    </div>
                    @include('partials.booking-transaction-detail', ['transaction' => $transaction])
                @empty
                    <div class="text-center p-5 bg-light rounded"><p class="text-muted">No ongoing transactions.</p></div>
                @endforelse
                <div class="mt-4">
                    {{ $ongoingTransactions->links() }}
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
                                    <h5 class="card-title mb-1">{{ $transaction->vehicle?->vehicleName?->name ?? 'N/A' }}</h5>
                                    <p class="card-text">{{ $transaction->vehicle?->vehicleType?->type ?? '-' }} / {{ $transaction->vehicle?->vehicleTransmission?->transmission ?? '-' }}</p>
                                </div>
                                <div class="col-md-5 text-md-end">
                                    <div class="mb-2">
                                        @if(in_array($transaction->transaction_status_id, [4, 5]))
                                            @if($transaction->userReview->isEmpty())
                                                <span class="badge rounded-pill text-bg-secondary">{{ __('booking-history.status.admin_review_pending') }}</span>
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
                                    
                                    @if($transaction->transaction_status_id != 7) 
                                        <p class="text-muted mb-1 small mt-1">{{ __('booking-history.label.total_price') }}</p>
                                        <h5 class="mb-3">Rp {{ number_format($transaction->total_price ?? 0, 0, ',', '.') }}</h5>
                                        <div>
                                            <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#detailModal{{ $transaction->id }}">
                                                @if (!$transaction->vehicleReview && in_array($transaction->transaction_status_id, [4, 5]))
                                                    {{ __('booking-history.button.leave_review') }}
                                                @else
                                                    {{ __('booking-history.button.view_detail') }}
                                                @endif
                                            </button>
                                            <a href="{{ route('receipt.download', $transaction) }}" class="btn btn-primary btn-sm rounded-pill px-3 ms-2">
                                                {{ __('booking-history.button.download_receipt') }}
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @include('partials.booking-transaction-detail', ['transaction' => $transaction])
                @empty
                    <div class="text-center p-5 bg-light rounded"><p class="text-muted">No transaction history.</p></div>
                @endforelse
                <div class="mt-4">
                    {{ $historyTransactions->links() }}
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            flatpickr(".date-picker", {
                dateFormat: "{{ __('booking-history.date_format_flatpickr') }}", // Mengatur format tanggal menjadi dd/mm/yyyy
            });
            
            @if ($errors->any() && session('open_modal'))
                var modalId = '#detailModal' + {{ session('open_modal') }};
                var errorModal = document.querySelector(modalId);
                if (errorModal) {
                    new bootstrap.Modal(errorModal).show();
                }
            @endif

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

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="{{ asset('build/assets/js/booking-history.js') }}"></script>
    
</x-layout>
</html>