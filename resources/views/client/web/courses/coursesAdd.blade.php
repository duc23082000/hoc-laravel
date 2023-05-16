@extends('layout.app')

@section('content')
<div class="right_col" role="main">
    <form action="{{ route('courses.addData') }}" method="POST" enctype="multipart/form-data">
        {{-- Tên course  --}}
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" class="form-control"
            id="name" name="name"
            value="@if (session('name')) {{ session('name') }}@else{{ old('name') }}@endif" placeholder="Name...">
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
            value="@if (session('price')) {{ session('price') }}@else{{ old('price') }}@endif" placeholder="Price...">
        </div>
        @error('price')
            <p style="color: red">{{ $message }}</p>
        @enderror

        {{-- Lựa chọn Category  --}}
        <div class="form-floating" style="width: 30%;">
            <select class="form-select" aria-label="Floating label select example"
                    id="floatingSelect" name="category">
              <option selected value="{{ old('category') ?? '' }}">{{ old('category') ?? 'Chọn dữ liệu' }}</option>
            
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
	    <textarea id="description" name="description" rows="10">{{ old('description') }}</textarea>

        {{-- Ảnh course  --}}
        <div class="mb-3">
            <label for="image">Image:</label>
            <input class="form-control form-control-sm" type="file" name="image" id="image">
        </div>

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