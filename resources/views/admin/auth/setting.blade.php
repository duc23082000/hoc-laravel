@extends('layout.app')

@section('css')
    <style>
        .box-show {
            margin: 5% 20% 10% 20%;

        }

        .content {
            margin: 5%;
            font-size: 1.3em;
        }

        img {
            width: 200px;
            height: auto;
        }
    </style>
@endsection

{{-- Course1 --}}
@section('content')
    <div class="right_col" role="main">
        <div class="border box-show">
            <div class="content">
                @if (!empty(Auth::user()->email_verified_at))
                    <h2>Password:</h2>
                    <a href="{{ route('onTwoKey') }}">{{ Auth::user()->two_key == 0 ? 'On Two Key' : 'Off Two Key' }}</a>
                @endif
            </div>
        </div>
    </div>
@endsection
