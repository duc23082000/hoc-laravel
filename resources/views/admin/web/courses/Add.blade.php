@extends('layout.app')

@section('content')
<div class="right_col" role="main">
    <form action="{{ route('courses.addData') }}" method="POST" enctype="multipart/form-data">
        {{-- Tên course  --}}
        <div class="form-group">
            <label for="name">@lang('content.name'):</label>
            <input type="text" class="form-control"
            id="name" name="name"
            value="{{ old('name') }}" placeholder="@lang('content.name')...">
        </div>
        @error('name')
            <p style="color: red">{{ $message }}</p>
        @enderror

        {{-- Giá course  --}}
        <div class="form-group">
            <label for="price">@lang('content.price'):</label>
            <input type="text" class="form-control"
            id="price" name="price"
            value="{{ old('price') }}" placeholder="@lang('content.price')...">
        </div>
        @error('price')
            <p style="color: red">{{ $message }}</p>
        @enderror

        {{-- Lựa chọn Category  --}}
        <div class="form-floating" style="width: 30%;">
            <select class="form-select" aria-label="Floating label select example"
                    id="floatingSelect" name="category">
              <option selected value="{{ old('category') ?? '' }}">Chọn dữ liệu</option>
            
              @foreach ($categorylist as $item)
                <option value="{{ $item->id }}">{{ $item->name }}</option>
              @endforeach

            </select>
            <label for="floatingSelect">@lang('content.category'):</label>
        </div>
        @error('category')
            <p style="color: red">{{ $message }}</p>
        @enderror

        {{-- Trạng thái khóa học  --}}        
        <div>
            @lang('content.status'):
            @foreach ($arrayCourseStatus as $key => $value)
            <input class="form-check-input" type="radio" name="status" value="{{ $value }}" @if($value == old('status') || $value == 0)checked @endif > @lang('content.courseStatus.' .$key)
            @endforeach
        </div>
        @error('status')
            <p style="color: red">{{ $message }}</p>
        @enderror

        {{-- Nội dung khóa học  --}}
        <label for="description">@lang('content.description'):</label>
	    <textarea id="description" name="description" rows="10">@if (session('description')) {{ session('description') }}@else{{ old('description') }}@endif</textarea>

        {{-- Ảnh course  --}}
        <div class="mb-3">
            <label for="image">@lang('content.image'):</label>
            <input class="form-control form-control-sm" type="file" name="image" id="image">
        </div>
        @error('image')
            <p style="color: red">{{ $message }}</p>
        @enderror

        @csrf
        <button type="submit" class="btn btn-primary">@lang('content.add')</button>
        <a href="{{ route('courses.list') }}" class="btn btn-secondary">@lang('content.back')</a>
    </form>
</div>
@endsection

@section('script')
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
	tinymce.init({
	selector: '#description'
	});
</script>
@endsection