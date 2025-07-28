@props([
    'title',
    'count' => 0,
    'href' => '#',
    'color' => 'primary'
])

@php
    $labelText = $label ?? $title;
@endphp

<div class="col-md-6 col-lg-4">
    <div class="card shadow-sm h-100">
        <div class="card-header bg-{{ $color }} text-white">
            {{ $title }}
        </div>
        <div class="card-body">
            <p>{{ __('admin_navbar.total') }} {{ $title }}: {{ $count }}</p>
            <a href="{{ $href }}" class="btn btn-sm btn-{{ $color }}">{{ __('admin_navbar.manage') }}</a>
        </div>
    </div>
</div>
