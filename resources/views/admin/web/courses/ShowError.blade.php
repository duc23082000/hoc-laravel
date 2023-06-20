@extends('layout.app')

@section('css')
    <style>
        .box-show{
            margin: 5% 20% 10% 20%;
            
        }

        .content{
            margin: 5%;
            font-size: 1.3em;
        }
        img{
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
            <h1>Tên File: {{ $error->name }}</h1>
            <p>{!! $error->notification !!}</p>
        </div>
    </div>
</div>
@endsection
