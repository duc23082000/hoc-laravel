@extends('layout.app')

@section('content')
<div class="right_col" role="main">
    <form action="{{ route('updateData') }}" method="POST">
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" class="form-control"
            id="name" name="name"
            value="{{ old('name') ?? $categoryEdit['name'] }}" placeholder="Name...">
        </div>
        @if (session('message2'))
          <p style="color: red">{{ session('message2') }}</p>
        @endif
        @error('name')
            <p style="color: red">{{ $message }}</p>
        @enderror
        <div class="form-group">
            <label for="order">Order:</label>
            <input type="text" class="form-control" 
            id="order" name="order"
            value="{{ old('order') ?? $categoryEdit['order'] }}" placeholder="Order...">
        </div>
        @error('order')
            <p style="color: red">{{ $message }}</p>
        @enderror
        @csrf
        @method('PUT')
        <button type="submit" class="btn btn-primary">Sửa</button>
        <a href="{{ route('categories.list') }}" class="btn btn-secondary">Thoát</a>
    </form>
</div>
@endsection



