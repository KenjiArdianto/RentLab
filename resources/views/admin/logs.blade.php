@extends('admin.master')

@section('content')

<div class="container-fluid justify-content-between align-items-center mb-4">
    <form action="{{ route('admin.logs') }}" method="GET">
        <input name="search" class="form-control border-dark w-50 mx-auto my-2" placeholder="{{ __('admin_search_hints.logs') }}" aria-label="Search" value="{{ request('search') }}">
        
    </form>
</div>  

<div class="container-fluid">
    <div class="table-responsive" style="overflow-y: hidden;">
        <table class="table table-striped table-bordered table-hover align-middle text-center">
            <thead class="table-light" style="position: sticky; top: 0; z-index: 1;">
                <tr>
                    <th class="text-nowrap">ID</th>
                    <th class="text-nowrap">{{ __('admin_tables.log_name') }}</th>
                    <th class="text-nowrap">{{ __('admin_tables.description') }}</th>
                    <th class="text-nowrap">{{ __('admin_tables.subject_type') }}</th>
                    <th class="text-nowrap">{{ __('admin_tables.event') }}</th>
                    <th class="text-nowrap">{{ __('admin_tables.subject_id') }}</th>
                    <th class="text-nowrap">{{ __('admin_tables.causer_type') }}</th>
                    <th class="text-nowrap">{{ __('admin_tables.causer_id') }}</th>
                    <th class="text-nowrap">{{ __('admin_tables.properties') }}</th>
                    <th class="text-nowrap">{{ __('admin_tables.batch_uuid') }}</th>
                    <th class="text-nowrap">{{ __('admin_tables.created_at') }}</th>

                </tr>
            </thead>
            <tbody>
            @foreach($logs as $log)
                <tr>
                    <td>{{ $log->id ?? 'N/A' }}</td>
                    <td>{{ $log->log_name ?? 'N/A' }}</td>
                    <td>{{ $log->description ?? 'N/A' }}</td>
                    <td>{{ $log->subject_type ?? 'N/A' }}</td>
                    <td>{{ $log->event ?? 'N/A' }}</td>
                    <td>{{ $log->subject_id ?? 'N/A' }}</td>
                    <td>{{ $log->causer_type ?? 'N/A' }}</td>
                    <td>{{ $log->causer_id ?? 'N/A' }}</td>
                    <td>{{ $log->properties ?? 'N/A' }}</td>
                    <td>{{ $log->batch_uuid ?? 'N/A' }}</td>
                    <td>{{ $log->created_at ?? 'N/A' }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="container d-flex justify-content-center" style="margin-top: 4vh; margin-bottom: 10vh;">
    {{ $logs->onEachSide(1)->links('pagination::bootstrap-5') }}
</div>

@endsection