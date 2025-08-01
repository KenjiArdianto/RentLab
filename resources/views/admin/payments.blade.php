@extends('admin.master')

@section('content')

<div class="container-fluid justify-content-between align-items-center mb-4">
    <form action="{{ route('admin.payments') }}" method="GET">
        <input name="search" class="form-control border-dark w-50 mx-auto my-2" placeholder="{{ __('admin_search_hints.payments') }}" aria-label="Search" value="{{ request('search') }}">
        
    </form>
</div>  

    <div class="container-fluid">
        <div class="table-responsive" style="overflow-y: hidden;">
            <table class="table table-striped table-bordered table-hover align-middle text-center">
                <thead class="table-light" style="position: sticky; top: 0; z-index: 1;">
                    <tr>
                        <th class="text-nowrap">ID</th>
                        <th class="text-nowrap">URL</th>
                        <th class="text-nowrap">{{ __('admin_tables.external_id') }}</th>
                        <th class="text-nowrap">{{ __('admin_tables.amount') }}</th>
                        <th class="text-nowrap">Status</th>
                        <th class="text-nowrap">{{ __('admin_tables.paid_at') }}</th>
                        <th class="text-nowrap">{{ __('admin_tables.payment_method') }}</th>
                        <th class="text-nowrap">{{ __('admin_tables.payment_channel') }}</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($payments as $payment)
                    <tr onclick="window.location='{{ route('admin.transactions') }}?search={{ rawurlencode('payment_id=' . $payment->id) }}';" style="cursor: pointer;">
                        <td>{{ $payment->id ?? 'N/A' }}</td>
                        <td>{{ $payment->url ?? 'N/A' }}</td>
                        <td>{{ $payment->external_id ?? 'N/A' }}</td>
                        <td>{{ $payment->amount ?? 'N/A' }}</td>
                        <td>{{ $payment->status ?? 'N/A' }}</td>
                        <td>{{ $payment->paid_at ?? 'N/A' }}</td>
                        <td>{{ $payment->payment_method ?? 'N/A' }}</td>
                        <td>{{ $payment->payment_channel ?? 'N/A' }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

<div class="container d-flex justify-content-center" style="margin-top: 4vh; margin-bottom: 10vh;">
    {{ $payments->onEachSide(1)->links('pagination::bootstrap-5') }}
</div>

@endsection