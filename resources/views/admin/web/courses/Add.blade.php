@extends('layout.app')

@section('content')
<div class="right_col" role="main">
    <form action="{{ route('courses.addData') }}" method="POST" enctype="multipart/form-data">
        {{-- Tên course  --}}
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" class="form-control"
            id="name" name="name"
            value="{{ old('name') }}" placeholder="Name...">
        </div>
        @error('name')
            <p style="color: red">{{ $message }}</p>
        @enderror

        {{-- Giá course  --}}
        <div class="form-group">
            <label for="price">Price:</label>
            <input type="text" class="form-control"
            id="price" name="price"
            value="{{ old('price') }}" placeholder="Price...">
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
            <label for="floatingSelect">Category:</label>
        </div>
        @error('category')
            <p style="color: red">{{ $message }}</p>
        @enderror

        {{-- Nội dung khóa học  --}}
        <label for="description">Description:</label>
	    <textarea id="description" name="description" rows="10">@if (session('description')) {{ session('description') }}@else{{ old('description') }}@endif</textarea>

        {{-- Ảnh course  --}}
        <div class="mb-3">
            <label for="image">Image:</label>
            <input class="form-control form-control-sm" type="file" name="image" id="image">
        </div>
        @if (session('message'))
            <p style="color: red">{{ session('message') }}</p>
        @endif
        @error('image')
            <p style="color: red">{{ $message }}</p>
        @enderror

        @csrf
        <button type="submit" class="btn btn-primary">Thêm</button>
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