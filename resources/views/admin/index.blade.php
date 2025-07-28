@extends('admin.master')

@section('content')
    <div class="row m-4 g-4">
        @foreach ($counts as $count)
            <x-admin.index-card 
            :title="$count['title']"
            :count="$count['count']"
            :href="$count['route']"
            :color="$count['color']"
            />
        @endforeach
    </div>
@endsection
