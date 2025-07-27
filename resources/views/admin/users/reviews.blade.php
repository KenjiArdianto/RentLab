@extends('admin.master')

@section('content')

@php
    use Illuminate\Support\Str;
@endphp

    <div class="text-center fw-bold fs-3 mb-2">
        User #{{ $user->id }}
    </div>

    <div class="d-flex justify-content-center gap-3 align-items-center my-2 mx-auto">
        <span class="badge bg-primary fs-6">
            Average Rating: {{ number_format($user->reviews->avg('rate'), 1) }}
        </span>
        <span class="badge bg-secondary fs-6">
            Reviews: {{ $user->reviews->count() }}
        </span>
    </div>

    <div class="container-fluid justify-content-between align-items-center mb-4">
        <form action="{{ route('admin.users.reviews', $user->id) }}" method="GET">
            <input name="search" class="form-control border-dark w-50 mx-auto my-2" placeholder="Format: Attribute1=Value1,Attribute2=Value2 ex: review_id=1,transaction_id=1,comment=bad,rating=4" aria-label="Search">
            
        </form>
    </div>  

    <div class="container-flex m-4">
        <table class="table table-bordered table-hover align-middle text-center mx-auto table-striped" style="cursor: pointer;">
            <thead class="table-light">
                <tr>
                    <th>Admin ID</th>
                    <th>Transaction ID</th>
                    <th>Comment</th>
                    <th>Rating</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($reviews as $review)
                    <tr data-bs-toggle="modal" data-bs-target="#editModal{{ $review->id }}">
                        <td>{{ $review->admin_id ?? 'N/A' }}</td>
                        <td>{{ $review->transaction_id ?? 'N/A' }}</td>
                        <td>{{ Str::limit($review->comment, 100, '...') ?? 'N/A' }}</td>
                        <td>{{ $review->rate ?? 'N/A' }}</td>
                    </tr>

                    <div class="modal fade" id="editModal{{ $review->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $review->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel{{ $review->id }}">Review - #{{ $review->id }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                    <div class="modal-body">
                                        <dl class="row mb-0">
                                            <dt class="col-sm-3">Admin ID</dt>
                                            <dd class="col-sm-9">{{ $review->admin_id ?? 'N/A' }}</dd>

                                            <dt class="col-sm-3">Transaction ID</dt>
                                            <dd class="col-sm-9">{{ $review->transaction_id ?? 'N/A' }}</dd>

                                            <dt class="col-sm-3">Review</dt>
                                            <dd class="col-sm-9">{{ $review->comment ?? 'N/A' }}</dd>

                                            <dt class="col-sm-3">Rating</dt>
                                            <dd class="col-sm-9">{{ $review->rate ?? 'N/A' }}</dd>
                                        </dl>
                                    </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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

    {{-- <script>
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
    </script> --}}

    <div class="container">
        {{ $reviews->onEachSide(5)->links('pagination::bootstrap-5') }}
    </div>

    <x-admin.feedback-modal/>

@endsection