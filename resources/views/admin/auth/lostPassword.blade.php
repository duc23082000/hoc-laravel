@extends('layout.auth')

@section('form')
    <form action="{{ route('checkEmail') }}" method="POST">
        <h1>Find Your Account</h1>
        <div>
        <input type="text" class="form-control" 
        name="email" value="@if (session('email')) {{ session('email') }} @endif"
        placeholder="Email"/>
        @if (session('message'))
            <p style="color: red">{{ session('message') }}</p>
        @endif
        @if (session('message2'))
            <p style="color:DodgerBlue">{{ session('message2') }}</p>
        @endif
        </div>
        <div>
        @csrf
        <button type="submit" class="btn btn-default submit">Search</button>

        <div class="separator">
        <span>back to</span>
        <a href="{{ route('login') }}" class="to_register"> Log in </a>
        </div>
        

        <div class="clearfix"></div>

        

        <div class="clearfix"></div>
    </form>
@endsection