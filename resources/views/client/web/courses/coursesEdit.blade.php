@extends('layout.app')

@section('css')
    <style>
        img{
            width: 200px;
            height: auto;
        }
    </style>
@endsection
@section('content')
<div class="right_col" role="main">
    <form action="{{ route('course.update') }}" method="POST" enctype="multipart/form-data">
        {{-- Tên course  --}}
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" class="form-control"
            id="name" name="name"
            value="@if (session('name')) {{ session('name') }}@else{{ old('name') ?? $courseList['course_name'] }}@endif" placeholder="Name...">
        </div>
        @if (session('message2'))
          <p style="color: red">{{ session('message2') }}</p>
        @endif
        @error('name')
            <p style="color: red">{{ $message }}</p>
        @enderror

        {{-- Giá course  --}}
        <div class="form-group">
            <label for="price">Price:</label>
            <input type="text" class="form-control"
            id="price" name="price"
            value="@if (session('price')) {{ session('price') }}@else{{ old('price') ?? $courseList['price'] }}@endif" placeholder="Price...">
        </div>
        @error('price')
            <p style="color: red">{{ $message }}</p>
        @enderror

        {{-- Lựa chọn Category  --}}
        <div class="form-floating" style="width: 30%;">
            <select class="form-select" aria-label="Floating label select example"
                    id="floatingSelect" name="category">
                <option selected value="@if (session('category')) {{ session('category') }}@else{{ old('category') ?? $category }}@endif">
                {{ old('category') ?? $category }}
                </option>
            
              @foreach ($categorylist as $item)
                <option value="{{ $item }}">{{ $item }}</option>
              @endforeach

            </select>
            <label for="floatingSelect">Category:</label>
        </div>
        @error('category')
            <p style="color: red">{{ $message }}</p>
        @enderror

        {{-- Nội dung khóa học  --}}
        <label for="description">Description:</label>
	    <textarea id="description" name="description" rows="10">@if (session('description')) {{ session('description') }}@else{{ old('description') ?? $courseList['description'] }}@endif</textarea>

        {{-- Ảnh course  --}}
        <div class="mb-3">
            <label for="image">Image:</label>
            <input class="form-control form-control-sm" style="width: 30%"
            type="file" name="image" id="image">
            <img src="{{ asset('storage/images/' . $courseList['image']) }}" alt="Ảnh">
        </div>
        

        @csrf
        @method('PUT')
        <button type="submit" class="btn btn-primary">Sửa</button>
        <a href="{{ route('courses.list') }}" class="btn btn-secondary">Thoát</a>
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