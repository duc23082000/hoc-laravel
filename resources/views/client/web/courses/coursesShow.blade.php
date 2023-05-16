@extends('layout.app')

@section('css')
    <style>
        .box-show{
            margin: 10% 20% 10% 20%;
            
        }

        .content{
            margin: 10%;
            font-size: 1.3em;
        }
        img{
            width: 200px;
            height: auto;
        }
    </style>
@endsection

@section('content')
<div class="right_col" role="main">
    <div class="border box-show">
        <div class="content">
            <img src="{{ asset('storage/images/' . $image) }}" alt="Ảnh">
            <h1>Course: {{ $name }}</h1>
            <p>Category: {{ $category }}</p>
            <p>Price: {{ $price }}</p>
            <p>Created by: {{ $created }} - <span>{{ $created_at }}</span></p> 
            <p>Modified by: {{ $modified }} - <span>{{ $updated_at }}</span></p>
            <p>Description: @php echo $description @endphp</p>
        </div>
    </div>
</div>
@endsection