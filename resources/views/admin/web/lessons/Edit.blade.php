@extends('layout.app')

@section('content')
<div class="right_col" role="main">
    <form action="" method="POST" enctype="multipart/form-data">
        {{-- Tên lesson  --}}
        <div class="form-group">
            <label for="name">@lang('content.name'):</label>
            <input type="text" class="form-control"
            id="name" name="name"
            value="{{ old('name') ?? $lesson->lesson_name }}" placeholder="@lang('content.name')...">
        </div>
        @error('name')
            <p style="color: red">{{ $message }}</p>
        @enderror

        {{-- Lựa chọn Courses  --}}
        <div class="form-floating" style="width: 30%;">
            <select class="form-select" aria-label="Floating label select example"
                    id="floatingSelect" name="course">
                                
              @foreach ($courselist as $item)
                <option value="{{ $item->id }}" @if($item->id == old('course') ?? $lesson->course->id) selected @endif>{{ $item->course_name }}</option>
              @endforeach

            </select>
            <label for="floatingSelect">@lang('content.course'):</label>
        </div>
        @error('course')
            <p style="color: red">{{ $message }}</p>
        @enderror

        {{-- Trạng thái lesson --}}
        <div>
            {{ __('content.status') }}:
            @foreach ($enum as $key => $value)
            <input class="form-check-input" type="radio" name="status" value="{{ $value }}" @if($value == (old('status') ?? $lesson->status) || $value == 0)checked @endif > {{ __('content.lessonStatus.' .$key) }}
            @endforeach
        </div>
        @error('status')
            <p style="color: red">{{ $message }}</p>
        @enderror

        {{-- Nội dung Lesson  --}}
        <label for="content">@lang('content.description'):</label>
	    <textarea id="content" name="content" rows="10">{{ old('content') ?? $lesson->content }}</textarea>

        {{-- Video lesson  --}}
        <div class="mb-3">
            <label for="video">@lang('content.image'):</label>
            <input class="form-control form-control-sm" type="file" name="video" id="video">
        </div>
        @error('video')
            <p style="color: red">{{ $message }}</p>
        @enderror

        @csrf
        @method('PUT')
        <button type="submit" class="btn btn-primary">@lang('content.edit')</button>
        <a href="{{ route('lesson.list') }}" class="btn btn-secondary">@lang('content.back')</a>
    </form>
</div>
@endsection

@section('script')
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
	tinymce.init({
	selector: '#content'
	});
</script>
@endsection