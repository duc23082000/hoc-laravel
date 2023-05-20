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

@section('content')
<div class="right_col" role="main">
    <div class="border box-show">
        <div class="content">
            @if (!empty($joinCategories[0]->image))
                <img src="{{ asset('storage/images/' . $joinCategories[0]->image) }}" alt="Ảnh">
            @endif
            <h1>Course: {{ $joinCategories[0]->course_name }}</h1>
            <p>Category: {{ $joinCategories[0]->name }}</p>
            <p>Price: {{ $joinCategories[0]->price }}$</p>
            <p>Created by: {{ $joinUserCreate[0]->email }} - <span>{{ $joinCategories[0]->created_at }}</span></p> 
            <p>Modified by: {{ $joinUserModify[0]->email }} - <span>{{ $joinCategories[0]->updated_at }}</span></p>
            <p>Description: @php echo $joinCategories[0]->description @endphp</p>
        </div>
    </div>
</div>
@endsection