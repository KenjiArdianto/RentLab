@extends('admin.master')

@section('content')

    <div class="container-fluid justify-content-between align-items-center mb-4">
        <form action="{{ route('admin.transactions.search') }}" method="GET">
            <input name="search" class="form-control border-dark w-50 mx-auto my-2" placeholder="Format: Attribute1=Value1,Attribute2=Value2 ex: transaction_id=1,start=2025-01-31,status=review_by_user" aria-label="Search">
            
        </form>
    </div> 
    
    <div class="container-flex m-4">
        <table class="table table-bordered table-hover align-middle text-center mx-auto" style="cursor: pointer;">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>User ID</th>
                    <th>Driver ID</th>
                    <th>Vehicle ID</th>
                    <th>Start</th>
                    <th>End</th>
                    <th>Return</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
            @foreach($transactions as $t)
                @php
                    // Map of status IDs to labels
                    $statusMap = [
                        0 => 'On Payment',
                        1 => 'On Booking',
                        2 => 'Car Taken',
                        3 => 'Review By Admin',
                        4 => 'Review By User',
                        5 => 'Closed',
                        6 => 'Canceled',
                    ];

                    // Get the label based on t->transactionStatus (0-6)
                    $statusText = $statusMap[$t->status] ?? 'Unknown';

                    // Badge color logic
                    $badgeClass = match($t->transaction_status_id) {
                        1 => 'text-bg-warning',  // On Payment
                        2 => 'text-bg-info',     // On Booking
                        3 => 'text-bg-primary',  // Car Taken
                        4 => 'text-bg-success',// Review By Admin
                        5 => 'text-bg-success',// Review By User
                        6 => 'text-bg-secondary',  // Closed
                        7 => 'text-bg-danger',   // Canceled
                        default => 'text-bg-dark',
                    };

                    
                @endphp

                <tr data-bs-toggle="modal" data-bs-target="#editModal{{ $t->id }}">
                    <td>{{ $t->id }}</td>
                    <td><strong>{{ $t->user?->username ?? 'N/A' }}</strong></td>
                    <td>{{ $t->user_id }}</td>
                    <td>{{ $t->driver_id }}</td>
                    <td>{{ $t->vehicle_id }}</td>
                    <td>{{ $t->start_book_date }}</td>
                    <td>{{ $t->end_book_date }}</td>
                    <td>{{ $t->return_date }}</td>
                    <td>
                        <span class="badge px-3 py-2 {{ $badgeClass }}" style="width: 7vw;">{{ $t->transactionStatus->status }}</span>
                    </td>
                </tr>

                <!-- Modal for this transaction -->
                <div class="modal fade" id="editModal{{ $t->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $t->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel{{ $t->id }}">Transaction Detail</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            {{-- Form for updating status --}}
                            <form action="{{ route('admin.transactions.update', $t->id) }}" method="POST">  
                                @csrf
                                <div class="modal-body">
                                    <dl class="row mb-0">
                                        <dt class="col-sm-3">User</dt>
                                        <dd class="col-sm-9">
                                            {{ $t->user?->username ?? 'N/A' }} (ID: {{ $t->user_id }})
                                        </dd>

                                        <dt class="col-sm-3">Driver ID</dt>
                                        <dd class="col-sm-9">{{ $t->driver_id }}</dd>

                                        <dt class="col-sm-3">Vehicle ID</dt>
                                        <dd class="col-sm-9">{{ $t->vehicle_id }}</dd>

                                        <dt class="col-sm-3">Start</dt>
                                        <dd class="col-sm-9">{{ $t->start_book_date }}</dd>

                                        <dt class="col-sm-3">End</dt>
                                        <dd class="col-sm-9">{{ $t->end_book_date }}</dd>

                                        <dt class="col-sm-3">Return</dt>
                                        <dd class="col-sm-9">{{ $t->return_date }}</dd>

                                        <dt class="col-sm-3">Status</dt>
                                        <dd class="col-sm-9">
                                            <select name="status" class="form-select">
                                                <option value="1" @selected($t->transaction_status_id == 1)>On Payment</option>
                                                <option value="2" @selected($t->transaction_status_id == 2)>On Booking</option>
                                                <option value="3" @selected($t->transaction_status_id == 3)>Car Taken</option>
                                                <option value="4" @selected($t->transaction_status_id == 4)>Review By Admin</option>
                                                <option value="5" @selected($t->transaction_status_id == 5)>Review By User</option>
                                                <option value="6" @selected($t->transaction_status_id == 6)>Closed</option>
                                                <option value="7" @selected($t->transaction_status_id == 7)>Canceled</option>
                                            </select>
                                        </dd>
                                    </dl>
                                    {{-- Show review & rating input if status == 4 --}}
                                    @if($t->transaction_status_id == 4)
                                        <dt class="col-sm-3">Review</dt>
                                        <dd class="col-sm-9">
                                            <textarea name="comment"
                                                    class="form-control" 
                                                    rows="3" 
                                                    maxlength="500" 
                                                    oninput="updateCharCount(this, 'reviewCounter-{{ $t->id }}')"
                                                    placeholder="Write your review here (max 500 characters)...">{{ old('review', $t->review ?? '') }}</textarea>
                                            <small class="text-muted">
                                                <span id="reviewCounter-{{ $t->id }}">0</span>/500 characters
                                            </small>
                                        </dd>
                                        <dt class="col-sm-3">Rating</dt>
                                        <dd class="col-sm-9">
                                            <select name="rating" class="form-select w-auto">
                                                <option value="1">1 Star</option>
                                                <option value="2">2 Stars</option>
                                                <option value="3">3 Stars</option>
                                                <option value="4">4 Stars</option>
                                                <option value="5">5 Stars</option>
                                            </select>
                                        </dd>
                                    @endif
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Apply</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            @endforeach
            </tbody>
        </table>
    </div>

    {{-- Success Modal (shows only if session has "success") --}}
    @if(session('success') || session('error'))
        <div class="modal fade" id="feedbackModal" tabindex="-1" aria-labelledby="feedbackModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header text-white py-2 {{ session('success') ? 'bg-success' : 'bg-danger' }}">
                        <h6 class="modal-title d-flex align-items-center" id="feedbackModalLabel">
                            {{ session('success') ? 'Success' : 'Error' }}
                        </h6>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-0">{{ session('success') ? session('success') : session('error') }}</p>
                    </div>
                    <div class="modal-footer py-2">
                        <button type="button" class="btn btn-sm {{ session('success') ? 'btn-success' : 'btn-danger' }}" data-bs-dismiss="modal">OK</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Auto‑show script --}}
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const modalEl = document.getElementById('feedbackModal');
                if (modalEl) {
                    const feedbackModal = new bootstrap.Modal(modalEl);
                    feedbackModal.show();

                    // Optional: auto-hide after 4 seconds
                    setTimeout(() => {
                        const backdrop = document.querySelector('.modal-backdrop');
                        feedbackModal.hide();
                        // (Backdrop will be removed by Bootstrap)
                    }, 4000);
                }
            });
        </script>
    @endif

    <div class="container">
        {{ $transactions->onEachSide(5)->links('pagination::bootstrap-5') }}
    </div>

    <script>
    function updateCharCount(textarea, counterId) {
        const counter = document.getElementById(counterId);
        if (counter) {
            counter.textContent = textarea.value.length;
        }
    }

    // Initialize counters for all textareas (on page load)
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('textarea[maxlength]').forEach((textarea) => {
            const counterId = textarea.getAttribute('oninput').match(/'(.*?)'/)[1];
            updateCharCount(textarea, counterId);
        });
    });
    </script>




@endsectiona