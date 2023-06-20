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
            @if (!empty($data->image))
                <img src="{{ asset('storage/images/' . $data->image) }}" alt="@lang('content.image')">
            @endif
            <h1>@lang('content.course'): {{ $data->course_name }}</h1>
            <p>@lang('content.category'): {{ $data->category->name }}</p>
            <p>@lang('content.price'): {{ $data->price }}$</p>
            <p>@lang('content.status'): {{ $data->status_name }}</p>
            <p>@lang('content.createdBy'): {{ $data->user_create->email }} - <span>{{ $data->created_at }}</span></p>
            <p>@lang('content.modifiedBy'): {{ $data->user_update->email }} - <span>{{ $data->updated_at }}</span></p>
            <p>@lang('content.description'): @php echo $data->description @endphp</p>
            <h3>@lang('content.lesson'):</h3>
            @foreach ($data->lessons as $key => $item)
                <p>{{ $key +1 }}: {{ $item->lesson_name }}</p>
            @endforeach

        </div>
    </div>
</div>
@endsection

{{-- Course2  --}}
{{-- @section('content')
<div class="right_col" role="main">
    <div class="border box-show">
        <div class="content">
            @if (!empty($joinData->image))
                <img src="{{ asset('storage/images/' . $joinData->image) }}" alt="Ảnh">
            @endif
            <h1>Course: {{ $joinData->course_name }}</h1>
            <p>Category: {{ $joinData->name }}</p>
            <p>Price: {{ $joinData->price }}$</p>
            <p>Created by: {{ $joinData->email }} - <span>{{ $joinData->created_at }}</span></p> 
            <p>Modified by: {{ $joinData->email }} - <span>{{ $joinData->updated_at }}</span></p>
            <p>Description: @php echo $joinData->description @endphp</p>
        </div>
    </div>
</div>
@endsection --}}