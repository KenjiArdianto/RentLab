@extends('admin.master')

@section('content')
    <div class="container-flex text-center my-4">
        <div class="row">
            <div class="col">
                <div class="text"></div>
                <h5><strong>LogID</strong></h5>
            </div>
            <div class="col">
                <h5><strong>Username</strong></h5>
            </div>
            <div class="col">
                <h5><strong>UserID</strong></h5>
            </div>
            <div class="col">
                <h5><strong>Action</strong></h5>
            </div>
            <div class="col">
                <h5><strong>Date Time</strong></h5>
            </div>
        </div>

        @php
    $logs = [
        [
            'id' => 101,
            'username' => 'admin',
            'user_id' => 1,
            'action' => 'Login',
            'created_at' => '2022-12-12 12:12:12',
        ],
        [
            'id' => 102,
            'username' => 'admin',
            'user_id' => 1,
            'action' => 'Login',
            'created_at' => '2022-12-12 12:12:12',
        ]
    ];
@endphp

@foreach ($logs as $log)
    <div class="row">
        <div class="col">
            <p>{{ $log['id'] }}</p>
        </div>
        <div class="col">
            <p>{{ $log['username'] }}</p>
        </div>
        <div class="col">
            <p>{{ $log['user_id'] }}</p>
        </div>
        <div class="col">
            <p>{{ $log['action'] }}</p>
        </div>
        <div class="col">
            <p>{{ $log['created_at'] }}</p>
        </div>
    </div>
@endforeach

    </div>
@endsection