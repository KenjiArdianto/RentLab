@extends('admin.master')

@section('content')

@php
    use Illuminate\Support\Str;
@endphp

<div class="text-center fw-bold fs-3 mb-2">
    {{ __('admin_tables.user') }} #{{ $user->id }}
</div>

<div class="d-flex justify-content-center gap-3 align-items-center my-2 mx-auto flex-wrap text-center">
    <span class="badge bg-primary fs-6">
        {{ __('admin_users.average_rating') }}: {{ number_format($user->reviews->avg('rate'), 1) }}
    </span>
    <span class="badge bg-secondary fs-6">
        {{ __('admin_tables.reviews') }}: {{ $user->reviews->count() }}
    </span>
</div>

<div class="container-fluid mb-4">
    <form action="{{ route('admin.users.reviews', $user->id) }}" method="GET">
        <input name="search" class="form-control border-dark mx-auto my-2" style="width: 50%;"
               placeholder="{{ __('admin_search_hints.user_reviews') }}"
               aria-label="Search" value="{{ request('search') }}">
    </form>
</div>

<div class="table-responsive m-4">
    <table class="table table-bordered table-hover align-middle text-center table-striped" style="cursor: pointer;">
        <thead class="table-light">
            <tr>
                <th class="responsive-th">{{ __('admin_tables.admin_id') }}</th>
                <th class="responsive-th">{{ __('admin_tables.transaction_id') }}</th>
                <th class="responsive-th">{{ __('admin_tables.comment') }}</th>
                <th class="responsive-th">{{ __('admin_tables.rating') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($reviews as $i => $review)
                <tr data-bs-toggle="modal" data-bs-target="#editModal{{ $review->id }}">
                    <td>{{ $review->admin_id ?? 'N/A' }}</td>
                    <td>{{ $review->transaction_id ?? 'N/A' }}</td>
                    <td id="comment{{ $i }}">{{ $review->comment }}</td>
                    <td>{{ $review->rate ?? 'N/A' }}</td>
                </tr>

                <!-- Modal -->
                <div class="modal fade" id="editModal{{ $review->id }}" tabindex="-1"
                     aria-labelledby="editModalLabel{{ $review->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel{{ $review->id }}">{{ __('admin_vehicles.edit_review') }} - #{{ $review->id }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <form id="updateReviewForm-{{ $review->id }}"
                                  action="{{ route('admin.users.reviews.update', ['user' => $user->id, 'userReview' => $review->id]) }}"
                                  method="POST">
                                @csrf
                                <div class="modal-body">
                                    <dl class="row mb-0">
                                        <dt class="col-sm-3">{{ __('admin_tables.admin_id') }}</dt>
                                        <dd class="col-sm-9">{{ $review->admin_id ?? 'N/A' }}</dd>

                                        <dt class="col-sm-3">{{ __('admin_tables.transaction_id') }}</dt>
                                        <dd class="col-sm-9">{{ $review->transaction_id }}</dd>

                                        <dt class="col-sm-3">{{  __('admin_tables.comment') }}</dt>
                                        <dd class="col-sm-9">
                                            <textarea name="comment"
                                                    class="form-control"
                                                    rows="3"
                                                    maxlength="250"
                                                    oninput="updateCharCount(this, 'reviewCounter-{{ $review->id }}')"
                                                    placeholder="{{  __('admin_vehicles.hint_review')}}">{{ $review->comment ?? '' }}</textarea>
                                            <small class="text-muted">
                                                <span id="reviewCounter-{{ $review->id }}">{{ strlen($review->comment ?? '') }}</span>/250 {{ __('admin_vehicles.characters') }}
                                            </small>
                                        </dd>

                                        <dt class="col-sm-3">{{  __('admin_tables.rating') }}</dt>
                                        <dd class="col-sm-9">
                                            <select name="rate" class="form-select w-auto">
                                                @for ($j = 1; $j <= 5; $j++)
                                                    <option value="{{ $j }}" {{ ($review->rate ?? '') == $j ? 'selected' : '' }}>
                                                        {{ $j }}
                                                    </option>
                                                @endfor
                                            </select>
                                        </dd>
                                    </dl>
                                </div>
                            </form>

                            <div class="modal-footer">
                                <form action="{{ route('admin.users.reviews.destroy', ['user' => $user->id, 'userReview' => $review->id]) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-danger">{{ __('admin_buttons.delete') }}</button>
                                </form>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('admin_buttons.close') }}</button>
                                <button type="submit" form="updateReviewForm-{{ $review->id }}" class="btn btn-primary">{{  __('admin_buttons.apply') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <tr>
                    <td colspan="4" class="text-center">{{  __('admin_vehicles.no_reviews') }}</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="container d-flex justify-content-center my-4">
    {{ $reviews->onEachSide(5)->links('pagination::bootstrap-5') }}
</div>


<!-- JS for responsive font and comment truncation -->
<script>
    window.onload = function () {
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

        // Resize <th> headers
        document.querySelectorAll('.responsive-th').forEach(th => {
            th.style.fontSize = fontSize;
        });

        // Resize <td> cells
        document.querySelectorAll('table td').forEach(td => {
            td.style.fontSize = fontSize;
        });

        // Truncate comments
        @foreach ($reviews as $i => $review)
            const commentEl{{ $i }} = document.getElementById("comment{{ $i }}");
            if (commentEl{{ $i }}) {
                const text{{ $i }} = commentEl{{ $i }}.textContent.trim();
                if (text{{ $i }}.length > limit) {
                    commentEl{{ $i }}.textContent = text{{ $i }}.substring(0, limit) + "...";
                }
            }
        @endforeach
    };
</script>

@endsection
