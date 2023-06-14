@extends('layout.app')
@section('content')
    <div class="right_col" role="main">
        <h1>Trang chủ</h1>
        
        @if (session('message'))
        <p class="alert alert-primary">{{ session('message') }}</p>
        @endif
    </div>
    
    {{-- {{ $username }} --}}
@endsection
