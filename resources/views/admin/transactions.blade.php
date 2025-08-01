@extends('admin.master')

@section('content')

<div class="container-fluid justify-content-between align-items-center mb-4">
    <form action="{{ route('admin.transactions') }}" method="GET">
        <input name="search" class="form-control border-dark w-50 mx-auto my-2" placeholder="{{ __('admin_search_hints.transactions') }}" aria-label="Search" value="{{ request('search') }}">
    </form>
</div> 

<div class="container-fluid">
    <div class="table-responsive" style="max-height: 70vh; overflow-y: hidden;">
        <table class="table table-striped table-bordered table-hover align-middle text-center" style="min-width: 1000px; cursor: pointer;">
            <thead class="table-light" style="position: sticky; top: 0; z-index: 1;">
                <tr>
                    <th>ID</th>
                    <th>{{ __('admin_tables.payment_id') }}</th>
                    <th>{{ __('admin_tables.username') }}</th>
                    <th>{{ __('admin_tables.user_id') }}</th>
                    <th>{{ __('admin_tables.driver_id') }}</th>
                    <th>{{ __('admin_tables.vehicle_id') }}</th>
                    <th>{{ __('admin_tables.start') }}</th>
                    <th>{{ __('admin_tables.end') }}</th>
                    <th>{{ __('admin_tables.return') }}</th>
                    <th>{{ __('admin_tables.price') }}</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
            @foreach($transactions as $t)
                @php
                    $statusMap = [
                        0 => 'On Payment',
                        1 => 'On Booking',
                        2 => 'Car Taken',
                        3 => 'Review By Admin',
                        4 => 'Review By User',
                        5 => 'Closed',
                        6 => 'Canceled',
                    ];

                    $statusText = $statusMap[$t->status] ?? 'Unknown';

                    $badgeClass = match($t->transaction_status_id) {
                        1 => 'text-bg-warning',
                        2 => 'text-bg-info',
                        3 => 'text-bg-primary',
                        4 => 'text-bg-success',
                        5 => 'text-bg-success',
                        6 => 'text-bg-secondary',
                        7 => 'text-bg-danger',
                        default => 'text-bg-dark',
                    };
                @endphp

                <tr data-bs-toggle="modal" data-bs-target="#editModal{{ $t->id }}">
                    <td>{{ $t->id ?? 'N/A' }}</td>
                    <td>{{ $t->payment_id ?? 'N/A' }}</td>
                    <td><strong>{{ $t->user->name ?? 'N/A' }}</strong></td>
                    <td>{{ $t->user_id ?? 'N/A' }}</td>
                    <td>{{ $t->driver_id ?? 'N/A' }}</td>
                    <td>{{ $t->vehicle_id ?? 'N/A' }}</td>
                    <td>{{ $t->start_book_date ?? 'N/A' }}</td>
                    <td>{{ $t->end_book_date ?? 'N/A' }}</td>
                    <td>{{ $t->return_date ?? 'N/A' }}</td>
                    <td>{{ $t->price ?? 'N/A' }}</td>
                    <td>
                        <span class="badge px-3 py-2 {{ $badgeClass }}" style="width: 20ch;">{{ $t->transactionStatus->status ?? 'N/A' }}</span>
                    </td>
                </tr>

                <!-- Modal for this transaction -->
                <div class="modal fade" id="editModal{{ $t->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $t->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel{{ $t->id }}">{{ __('admin_transactions.transaction_detail') }} - #{{ $t->id }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <form action="{{ route('admin.transactions.update', $t->id) }}" method="POST">  
                                @csrf
                                <div class="modal-body">
                                    <dl class="row mb-0">
                                        <dt class="col-sm-3">{{ __('admin_tables.user') }}</dt>
                                        <dd class="col-sm-9">
                                            {{ $t->user->name ?? 'N/A' }} (ID: {{ $t->user_id }})
                                        </dd>

                                        <dt class="col-sm-3">{{ __('admin_tables.driver_id') }}</dt>
                                        <dd class="col-sm-9">{{ $t->driver_id }}</dd>

                                        <dt class="col-sm-3">{{ __('admin_tables.vehicle_id') }}</dt>
                                        <dd class="col-sm-9">{{ $t->vehicle_id }}</dd>

                                        <dt class="col-sm-3">{{ __('admin_tables.start') }}</dt>
                                        <dd class="col-sm-9">{{ $t->start_book_date }}</dd>

                                        <dt class="col-sm-3">{{ __('admin_tables.end') }}</dt>
                                        <dd class="col-sm-9">{{ $t->end_book_date }}</dd>

                                        <dt class="col-sm-3">{{ __('admin_tables.return') }}</dt>
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

                                    @if($t->transaction_status_id == 4)
                                        <dt class="col-sm-3">{{ __('admin_tables.reviews') }}</dt>
                                        <dd class="col-sm-9">
                                            <textarea name="comment"
                                                    class="form-control" 
                                                    rows="3" 
                                                    maxlength="250" 
                                                    oninput="updateCharCount(this, 'reviewCounter-{{ $t->id }}')"
                                                    placeholder="{{ __('admin_transactions.comment_hint') }}"></textarea>
                                            <small class="text-muted">
                                                <span id="reviewCounter-{{ $t->id }}">0</span>/250 {{ __('admin_transactions.characters') }}
                                            </small>
                                        </dd>

                                        <dt class="col-sm-3">{{ __('admin_tables.rating') }}</dt>
                                        <dd class="col-sm-9">
                                            <select name="rating" class="form-select w-auto">
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                            </select>
                                        </dd>
                                    @endif
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{  __('admin_transactions.close') }}</button>
                                    <button type="submit" class="btn btn-primary">{{  __('admin_transactions.apply') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="container d-flex justify-content-center my-4">
    {{ $transactions->onEachSide(5)->links('pagination::bootstrap-5') }}
</div>

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
});
</script>

@endsection
