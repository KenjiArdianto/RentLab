<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking History</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    {{-- Path Anda tidak diubah --}}
    <link rel="stylesheet" href="{{ asset('build/assets/css/booking-history.css') }}">
</head>
<body>
    <div class="container my-5">
        <div class="transaction-controls p-3 rounded-4 bg-white shadow-sm">
            <nav class="nav nav-pills nav-fill mb-4" id="transaction-toggle">
                <a class="nav-link active" href="#ongoing-content" data-bs-toggle="tab">On-going</a>
                <a class="nav-link" href="#history-content" data-bs-toggle="tab">History</a>
            </nav>
            
            <form action="{{ route('booking.history') }}" method="GET">
                <input type="hidden" name="tab" value="{{ request('tab', 'ongoing') }}">
                <div class="row g-2 align-items-center">
                    <div class="col-md-5">
                        <input type="text" name="search" class="form-control" placeholder="Cari nama kendaraan..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" title="Start Date">
                    </div>
                    <div class="col-md-3">
                        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" title="End Date">
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-dark w-100">Search</button>
                    </div>
                </div>
            </form>
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
                                        ];
                                        $currentStatus = $statusConfig[$transaction->status] ?? ['text' => 'Unknown', 'bg' => 'text-bg-dark'];
                                    @endphp
                                    <span class="badge rounded-pill {{ $currentStatus['bg'] }} mb-2">{{ $currentStatus['text'] }}</span>
                                    <p class="text-muted mb-1 small">Total Price</p>
                                    <h5 class="mb-3">Rp {{ number_format($transaction->total_price ?? 0, 0, ',', '.') }}</h5>
                                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#detailModal{{ $transaction->id }}">View Detail</button>
                                    <a href="{{ route('receipt.download', $transaction) }}" class="btn btn-primary btn-sm rounded-pill px-3 ms-2">Download Receipt</a>
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
                                    <div class="mb-2">
                                        @if(in_array($transaction->status, [4, 5])) {{-- Jika statusnya masih dalam tahap review --}}
                                            
                                            {{-- Tampilkan jika admin BELUM review --}}
                                            @if(!$transaction->userReview)
                                                <span class="badge rounded-pill text-bg-secondary">Admin Review Pending</span>
                                            @endif

                                            {{-- Tampilkan jika user BELUM review --}}
                                            @if(!$transaction->vehicleReview)
                                                <span class="badge rounded-pill text-bg-warning text-dark">Awaiting Your Review</span>
                                            @endif

                                        @elseif($transaction->status == 6)
                                            <span class="badge rounded-pill text-bg-success">Closed</span>
                                        @elseif($transaction->status == 7)
                                            <span class="badge rounded-pill text-bg-danger">Canceled</span>
                                        @endif
                                    </div>
                                    
                                    {{-- 2. LOGIKA UNTUK MENAMPILKAN HARGA & TOMBOL --}}
                                    @if($transaction->status != 7) {{-- Jangan tampilkan apapun lagi jika statusnya Canceled --}}
                                        <p class="text-muted mb-1 small mt-1">Total Price</p>
                                        <h5 class="mb-3">Rp {{ number_format($transaction->total_price ?? 0, 0, ',', '.') }}</h5>
                                        
                                        <div>
                                            {{-- Tombol "Leave a Review" atau "View Detail" --}}
                                            <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#detailModal{{ $transaction->id }}">
                                                {{-- Jika user belum mereview kendaraan & statusnya masih dalam tahap review, ganti teks tombol --}}
                                                @if (!$transaction->vehicleReview && in_array($transaction->status, [4, 5]))
                                                    Leave a Review
                                                @else
                                                    View Detail
                                                @endif
                                            </button>
                                            
                                            {{-- Tombol Download Receipt selalu ada --}}
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
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Cek apakah ada session 'open_modal' yang dikirim dari controller
            @if ($errors->any() && session('open_modal'))
                // Ambil ID modal yang harus dibuka dari session
                var modalId = '#detailModal' + {{ session('open_modal') }};
                var errorModal = document.querySelector(modalId);
                
                // Jika elemen modalnya ada di halaman, buka secara otomatis
                if (errorModal) {
                    new bootstrap.Modal(errorModal).show();
                }
            @endif
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="{{ asset('build/assets/js/booking-history.js') }}"></script>
    
</body>
</html>