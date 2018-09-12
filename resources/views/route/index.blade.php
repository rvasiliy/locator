@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <ul>
                    @foreach($users as $user)
                        <li>
                            <a href="{{ route('route_detail', ['user_id' => $user->id]) }}">
                                {{ $user->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="col-md-9">
                {!! $map['js'] !!}
                {!! $map['html'] !!}
            </div>
        </div>
    </div>
@endsection
