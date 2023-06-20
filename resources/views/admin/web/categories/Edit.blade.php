@extends('layout.app')

@section('content')
<div class="right_col" role="main">
    <form action="" method="POST">
        <div class="form-group">
            <label for="name">@lang('content.name'):</label>
            <input type="text" class="form-control"
            id="name" name="name"
            value="{{ old('name') ?? $categoryEdit['name'] }}" placeholder="@lang('content.name')...">
        </div>
        @error('name')
            <p style="color: red">{{ $message }}</p>
        @enderror

        <div class="form-group">
            <label for="order">@lang('content.order'):</label>
            <input type="text" class="form-control"
            id="order" name="order"
            value="{{ old('order') ?? $categoryEdit['order'] }}" placeholder="@lang('content.price')...">
        </div>
        @error('order')
            <p style="color: red">{{ $message }}</p>
        @enderror
        
        <input type="hidden" name="id" value="{{ $id }}">

        @csrf
        @method('PUT')
        <button type="submit" class="btn btn-primary">@lang('content.edit')</button>
        <a href="{{ route('categories.list') }}" class="btn btn-secondary">@lang('content.back')</a>
    </form>
</div>
@endsection



