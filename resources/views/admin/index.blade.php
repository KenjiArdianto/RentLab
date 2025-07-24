@extends('admin.master')

@section('content')
<div class="row g-4">
    @foreach ($counts as $count)
        <x-admin.index-card 
            :title="$count['title']"
            :count="$count['count']"
            :href="$count['route']"
            :label="$count['label']"
            :color="$count['color']"
        />
    @endforeach
</div>
@endsection
