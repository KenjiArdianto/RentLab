<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking History</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    {{-- Path Anda tidak diubah --}}
    <link rel="stylesheet" href="{{ asset('build/assets/css/booking-history.css') }}">
</head>
<body>
    <div class="container my-5">
        <div class="input-group input-group-lg mb-4">
            <input type="text" class="form-control" placeholder="Cari di Rentlab" aria-label="Cari di Rentlab">
            <span class="input-group-text bg-white"><i class="bi bi-clipboard-check"></i></span>
        </div>
        <div class="transaction-controls p-3 rounded-4 bg-white shadow-sm">
            <nav class="nav nav-pills nav-fill" id="transaction-toggle">
                <a class="nav-link active" href="#ongoing-content" data-bs-toggle="tab">On-going</a>
                <a class="nav-link" href="#history-content" data-bs-toggle="tab">History</a>
            </nav>
        </div>

        <div class="tab-content mt-4" id="myTabContent">
            {{-- ================= TAB ON-GOING ================= --}}
            <div class="tab-pane fade show active" id="ongoing-content" role="tabpanel">
                @forelse ($ongoingTransactions as $transaction)
                    {{-- Kartu Transaksi --}}
                    <div class="card shadow-sm mb-3">
                        <div class="card-body">
                            <div class="row g-3 align-items-center">
                                <div class="col-md-2">
                                    <img src="{{ $transaction->vehicle?->main_image ?? 'https://via.placeholder.com/150' }}" class="img-fluid rounded" alt="Vehicle Image">
                                </div>
                                <div class="col-md-5">
                                    <p class="text-muted mb-1 small">{{ \Carbon\Carbon::parse($transaction->start_book_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($transaction->end_book_date)->format('d M Y') }}</p>
                                    <h5 class="card-title mb-1">{{ $transaction->vehicle?->vehicleName?->name ?? 'N/A' }}</h5>
                                    <p class="card-text">{{ $transaction->vehicle?->vehicleType?->type ?? '-' }} / {{ $transaction->vehicle?->vehicleTransmission?->transmission ?? '-' }}</p>
                                </div>
                                <div class="col-md-5 text-md-end">
                                    @php
                                        // Daftar status sesuai yang Anda berikan
                                        $statusConfig = [
                                            1 => ['text' => 'On Payment', 'bg' => 'text-bg-warning'],
                                            2 => ['text' => 'On Booking', 'bg' => 'text-bg-info'],
                                            3 => ['text' => 'Vehicle Taken', 'bg' => 'text-bg-primary'],
                                            4 => ['text' => 'Review By Admin', 'bg' => 'text-bg-secondary'],
                                            5 => ['text' => 'Review By User', 'bg' => 'text-bg-warning text-dark'], // Ubah agar lebih terlihat
                                            6 => ['text' => 'Closed', 'bg' => 'text-bg-success'],
                                            7 => ['text' => 'Canceled', 'bg' => 'text-bg-danger'],
                                        ];
                                        $currentStatus = $statusConfig[$transaction->status] ?? ['text' => 'Unknown', 'bg' => 'text-bg-dark'];
                                    @endphp

                                    {{-- KONDISI 1: JIKA STATUS DIBATALKAN --}}
                                    @if ($transaction->status == 6)
                                        <span class="badge rounded-pill {{ $currentStatus['bg'] }}">{{ $currentStatus['text'] }}</span>
                                    
                                    {{-- KONDISI 2: UNTUK SEMUA STATUS LAINNYA --}}
                                    @else
                                        {{-- Tampilkan badge status utama --}}
                                        <span class="badge rounded-pill {{ $currentStatus['bg'] }} mb-2">{{ $currentStatus['text'] }}</span>
                                        
                                        {{-- JIKA statusnya "Review by User", tampilkan badge tambahan --}}
                                        @if ($transaction->status == 4)
                                            <span class="badge rounded-pill bg-warning text-dark mb-2">Awaiting Review</span>
                                        @endif

                                        <p class="text-muted mb-1 small mt-1">Total Price</p>
                                        <h5 class="mb-3">Rp {{ number_format($transaction->total_price ?? 0, 0, ',', '.') }}</h5>
                                        
                                        <div class="action-buttons">
                                            <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#detailModal{{ $transaction->id }}">
                                                View Detail
                                            </button>
                                            <a href="{{ route('receipt.download', $transaction) }}" class="btn btn-primary btn-sm rounded-pill px-3 ms-2">
                                                Download Receipt
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Modal Detail Transaksi --}}
                    @include('partials.booking-transaction-detail', ['transaction' => $transaction])

                @empty
                    <div class="text-center p-5 bg-light rounded"><p class="text-muted">No ongoing transactions.</p></div>
                @endforelse
            </div>

            {{-- ================= TAB HISTORY ================= --}}
            <div class="tab-pane fade" id="history-content" role="tabpanel">
                @forelse ($historyTransactions as $transaction)
                    {{-- Kartu Transaksi --}}
                    <div class="card shadow-sm mb-3">
                        <div class="card-body">
                            <div class="row g-3 align-items-center">
                                <div class="col-md-2">
                                    <img src="{{ $transaction->vehicle?->main_image ?? 'https://via.placeholder.com/150' }}" class="img-fluid rounded" alt="Vehicle Image">
                                </div>
                                <div class="col-md-5">
                                    <p class="text-muted mb-1 small">{{ \Carbon\Carbon::parse($transaction->start_book_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($transaction->end_book_date)->format('d M Y') }}</p>
                                    <h5 class="card-title mb-1">{{ $transaction->vehicle?->vehicleName?->name ?? 'N/A' }}</h5>
                                    <p class="card-text">{{ $transaction->vehicle?->vehicleType?->type ?? '-' }} / {{ $transaction->vehicle?->vehicleTransmission?->transmission ?? '-' }}</p>
                                </div>
                                <div class="col-md-5 text-md-end">
                                    @php
                                        // Daftar status sesuai yang Anda berikan
                                        $statusConfig = [
                                            1 => ['text' => 'On Payment', 'bg' => 'text-bg-warning'],
                                            2 => ['text' => 'On Booking', 'bg' => 'text-bg-info'],
                                            3 => ['text' => 'Vehicle Taken', 'bg' => 'text-bg-primary'],
                                            4 => ['text' => 'Review By Admin', 'bg' => 'text-bg-secondary'],
                                            5 => ['text' => 'Review By User', 'bg' => 'text-bg-warning'], // Ini yang akan kita target
                                            6 => ['text' => 'Closed', 'bg' => 'text-bg-success'],
                                            7 => ['text' => 'Canceled', 'bg' => 'text-bg-danger'],
                                        ];
                                        $currentStatus = $statusConfig[$transaction->status] ?? ['text' => 'Unknown', 'bg' => 'text-bg-dark'];
                                    @endphp

                                    {{-- KONDISI 1: JIKA STATUS DIBATALKAN --}}
                                    @if ($transaction->status == 7)
                                        <span class="badge rounded-pill {{ $currentStatus['bg'] }}">{{ $currentStatus['text'] }}</span>

                                    {{-- KONDISI 2: UNTUK SEMUA STATUS LAINNYA --}}
                                    @elseif ($transaction->status == 5)
                                        <span class="badge rounded-pill bg-warning text-dark mb-2">Awaiting Review</span>
                                        <p class="text-muted mb-1 small mt-1">Total Price</p>
                                        <h5 class="mb-3">Rp {{ number_format($transaction->total_price ?? 0, 0, ',', '.') }}</h5>
                                        <div class="action-buttons">
                                            {{-- Tombol ini langsung membuka modal dengan form review --}}
                                            <button type="button" class="btn btn-primary btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#detailModal{{ $transaction->id }}">
                                                Leave a Review
                                            </button>
                                        </div>
                                    
                                    {{-- 3. Untuk semua status lainnya (On Payment, On Booking, Closed, dll) --}}
                                    @else
                                        <span class="badge rounded-pill {{ $currentStatus['bg'] }} mb-2">{{ $currentStatus['text'] }}</span>
                                        <p class="text-muted mb-1 small mt-1">Total Price</p>
                                        <h5 class="mb-3">Rp {{ number_format($transaction->total_price ?? 0, 0, ',', '.') }}</h5>
                                        <div class="action-buttons">
                                            <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#detailModal{{ $transaction->id }}">
                                                View Detail
                                            </button>
                                            <a href="{{ route('receipt.download', $transaction) }}" class="btn btn-primary btn-sm rounded-pill px-3 ms-2">
                                                Download Receipt
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Modal Detail Transaksi --}}
                    @include('partials.booking-transaction-detail', ['transaction' => $transaction])

                @empty
                    <div class="text-center p-5 bg-light rounded"><p class="text-muted">No transaction history.</p></div>
                @endforelse
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('build/assets/js/booking-history.js') }}"></script>
</body>
</html>