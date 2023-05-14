@extends('layout.app')

@section('content')
<div class="right_col" role="main">
    <form action="{{ route('addData') }}" method="POST">
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
        <div class="form-group">
            <label for="order">Order:</label>
            <input type="text" class="form-control" 
            id="order" name="order"
            value="@if (session('order')) {{ session('order') }}@else{{ old('order') }}@endif" placeholder="Order...">
        </div>
        @error('order')
            <p style="color: red">{{ $message }}</p>
        @enderror
        @csrf
        <button type="submit" class="btn btn-primary">Thêm</button>
        <a href="{{ route('categories.list') }}" class="btn btn-secondary">Thoát</a>
    </form>

</div>
@endsection



