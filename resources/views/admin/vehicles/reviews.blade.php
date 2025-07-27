@extends('admin.master')

@section('content')

@php
    use Illuminate\Support\Str;
@endphp

<div class="text-center fw-bold fs-3 mb-2">
    Vehicle #{{ $vehicle->id }}
</div>

<div class="d-flex justify-content-center gap-3 align-items-center my-2 mx-auto flex-wrap text-center">
    <span class="badge bg-primary fs-6">
        Average Rating: {{ number_format($vehicle->vehicleReview->avg('rate'), 1) }}
    </span>
    <span class="badge bg-secondary fs-6">
        Reviews: {{ $vehicle->vehicleReview->count() }}
    </span>
</div>

<div class="container-fluid mb-4">
    <form action="{{ route('admin.vehicles.reviews', $vehicle->id) }}" method="GET">
        <input name="search" class="form-control border-dark mx-auto my-2" style="width: 50%;"
               placeholder="Format: Attribute1=Value1,Attribute2=Value2 ex: review_id=1,transaction_id=1,comment=bad,rating=4"
               aria-label="Search">
    </form>
</div>

<div class="table-responsive m-4">
    <table class="table table-bordered table-hover align-middle text-center table-striped" style="cursor: pointer;">
        <thead class="table-light">
            <tr>
                <th class="responsive-th">User ID</th>
                <th class="responsive-th">Transaction ID</th>
                <th class="responsive-th">Comment</th>
                <th class="responsive-th">Rating</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($reviews as $i => $review)
                <tr data-bs-toggle="modal" data-bs-target="#editModal{{ $review->id }}">
                    <td>{{ $review->user_id }}</td>
                    <td>{{ $review->transaction_id }}</td>
                    <td id="comment{{ $i }}">{{ $review->comment }}</td>
                    <td>{{ $review->rate ?? 'N/A' }}</td>
                </tr>

                <!-- Modal -->
                <div class="modal fade" id="editModal{{ $review->id }}" tabindex="-1"
                     aria-labelledby="editModalLabel{{ $review->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel{{ $review->id }}">Edit Review - #{{ $review->id }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <form id="updateReviewForm-{{ $review->id }}"
                                  action="{{ route('admin.vehicles.reviews.update', ['vehicle' => $vehicle->id, 'vehicleReview' => $review->id]) }}"
                                  method="POST">
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
                                                @for ($j = 1; $j <= 5; $j++)
                                                    <option value="{{ $j }}" {{ ($review->rate ?? '') == $j ? 'selected' : '' }}>
                                                        {{ $j }} Star{{ $j > 1 ? 's' : '' }}
                                                    </option>
                                                @endfor
                                            </select>
                                        </dd>
                                    </dl>
                                </div>
                            </form>

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

<div class="container">
    {{ $reviews->onEachSide(5)->links('pagination::bootstrap-5') }}
</div>

<x-admin.feedback-modal/>

<script>
    function updateCharCount(textarea, counterId) {
        const counter = document.getElementById(counterId);
        if (counter) {
            counter.textContent = textarea.value.length;
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('textarea[maxlength]').forEach((textarea) => {
            const counterId = textarea.getAttribute('oninput').match(/'(.*?)'/)[1];
            updateCharCount(textarea, counterId);
        });

        const width = window.innerWidth;
        let fontSize = "1rem";
        let limit = 100;

        if (width < 576) {
            fontSize = "0.6rem";
            limit = 20;
        } else if (width < 768) {
            fontSize = "0.8rem";
            limit = 40;
        } else {
            fontSize = "1rem";
            limit = 60;
        }

        document.querySelectorAll('.responsive-th').forEach(th => {
            th.style.fontSize = fontSize;
        });

        document.querySelectorAll('table td').forEach(td => {
            td.style.fontSize = fontSize;
        });

        @foreach ($reviews as $i => $review)
            const commentEl{{ $i }} = document.getElementById("comment{{ $i }}");
            if (commentEl{{ $i }}) {
                const text{{ $i }} = commentEl{{ $i }}.textContent.trim();
                if (text{{ $i }}.length > limit) {
                    commentEl{{ $i }}.textContent = text{{ $i }}.substring(0, limit) + "...";
                }
            }
        @endforeach
    });
</script>

@endsection
