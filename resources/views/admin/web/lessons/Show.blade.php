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
        p{
            font-size: 0.7em;
        }
    </style>
@endsection

{{-- Course1 --}}
@section('content')
<div class="right_col" role="main">
    <div class="border box-show">
        <div class="content">
            @if (!empty($data->video))
                <video width="720" height="auto" controls>
                    <source src="{{ asset('storage/videos/' . $data->video) }}" type="video/mp4">
                </video>
            @endif
            <h1>@lang('content.lesson'): {{ $data->lesson_name }}</h1>
            <h4>@lang('content.description'): @php echo $data->content @endphp</h4>
            <p>@lang('content.course'): {{ $data->course->course_name }}</p>
            {{-- <p>@lang('content.status'): {{ $data->status_name }}</p> --}}
            <p>@lang('content.createdBy'): {{ $data->user_create->email }} - <span>{{ $data->created_at }}</span></p>
            <p>@lang('content.modifiedBy'): {{ $data->user_update->email }} - <span>{{ $data->updated_at }}</span></p>
            
        </div>
    </div>
</div>
@endsection