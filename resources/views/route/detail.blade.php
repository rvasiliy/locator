@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <a href="{{ route('routes') }}">Back</a>
        </div>
        <div class="row">
            <div class="col-md-12">
                <ul>
                    <li><b>Name:</b> {{ $user->name }}</li>
                    <li><b>Email:</b> {{ $user->email }}</li>
                    <li><b>Phone:</b> {{ $user->detail->phone }}</li>
                </ul>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                {!! $map['js'] !!}
                {!! $map['html'] !!}
            </div>
        </div>
    </div>
@endsection