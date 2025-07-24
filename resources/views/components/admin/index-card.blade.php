@props([
    'title',
    'count' => 0,
    'href' => '#',
    'label' => null,
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
            <p>Total {{ $labelText }}: {{ $count }}</p>
            <a href="{{ $href }}" class="btn btn-sm btn-{{ $color }}">Manage</a>
        </div>
    </div>
</div>
