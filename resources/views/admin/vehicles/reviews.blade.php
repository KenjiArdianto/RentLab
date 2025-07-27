@extends('admin.master')

@section('content')

@php
    use Illuminate\Support\Str;
@endphp

    <div class="text-center fw-bold fs-3 mb-2">
        Vehicle #{{ $vehicle->id }}
    </div>

    <div class="d-flex justify-content-center gap-3 align-items-center my-2 mx-auto">
        <span class="badge bg-primary fs-6">
            Average Rating: {{ number_format($vehicle->vehicleReview->avg('rate'), 1) }}
        </span>
        <span class="badge bg-secondary fs-6">
            Reviews: {{ $vehicle->vehicleReview->count() }}
        </span>
    </div>

    <div class="container-fluid justify-content-between align-items-center mb-4">
        <form action="{{ route('admin.vehicles.reviews', $vehicle->id) }}" method="GET">
            <input name="search" class="form-control border-dark w-50 mx-auto my-2" placeholder="Format: Attribute1=Value1,Attribute2=Value2 ex: review_id=1,transaction_id=1,comment=bad,rating=4" aria-label="Search">
            
        </form>
    </div>  

    <div class="container-flex m-4">
        <table class="table table-bordered table-hover align-middle text-center mx-auto table-striped" style="cursor: pointer;">
            <thead class="table-light">
                <tr>
                    <th>User ID</th>
                    <th>Transaction ID</th>
                    <th>Comment</th>
                    <th>Rating</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($reviews as $review)
                    <tr data-bs-toggle="modal" data-bs-target="#editModal{{ $review->id }}">
                        <td>{{ $review->user_id }}</td>
                        <td>{{ $review->transaction_id }}</td>
                        <td>{{ Str::limit($review->comment, 100, '...') }}</td>
                        <td>{{ $review->rate }}</td>
                    </tr>

                    <!-- View Review Modal -->
                    <div class="modal fade" id="editModal{{ $review->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $review->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel{{ $review->id }}">Edit Review - #{{ $review->id }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                {{-- Update Form --}}
                                <form id="updateReviewForm-{{ $review->id }}" action="{{ route('admin.vehicles.reviews.update', ['vehicle' => $vehicle->id, 'vehicleReview' => $review->id]) }}" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <dl class="row mb-0">
                                            <dt class="col-sm-3">User ID</dt>
                                            <dd class="col-sm-9">{{ $review->user_id ?? 'N/A' }}</dd>

                                            <dt class="col-sm-3">Transaction ID</dt>
                                            <dd class="col-sm-9">{{ $review->transaction_id }}</dd>

                                            <dt class="col-sm-3">Review</dt>
                                            <dd class="col-sm-9">
                                                <textarea name="comment"
                                                        class="form-control"
                                                        rows="3"
                                                        maxlength="250"
                                                        oninput="updateCharCount(this, 'reviewCounter-{{ $review->id }}')"
                                                        placeholder="Write your review here (max 250 characters)...">{{ $review->comment ?? '' }}</textarea>
                                                <small class="text-muted">
                                                    <span id="reviewCounter-{{ $review->id }}">{{ strlen($review->comment ?? '') }}</span>/250 characters
                                                </small>
                                            </dd>

                                            <dt class="col-sm-3">Rating</dt>
                                            <dd class="col-sm-9">
                                                <select name="rate" class="form-select w-auto">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <option value="{{ $i }}" {{ ($review->rate ?? '') == $i ? 'selected' : '' }}>
                                                            {{ $i }} Star{{ $i > 1 ? 's' : '' }}
                                                        </option>
                                                    @endfor
                                                </select>
                                            </dd>
                                        </dl>
                                    </div>
                                </form>

                                {{-- Footer with Delete Form --}}
                                <div class="modal-footer">
                                    <form action="{{ route('admin.vehicles.reviews.destroy', ['vehicle' => $vehicle->id, 'vehicleReview' => $review->id]) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" form="updateReviewForm-{{ $review->id }}" class="btn btn-primary">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>

                @empty
                    <tr>
                        <td colspan="4" class="text-center">No reviews found.</td>
                    </tr>
                @endforelse
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

    <div class="container">
        {{ $reviews->onEachSide(5)->links('pagination::bootstrap-5') }}
    </div>

@endsection