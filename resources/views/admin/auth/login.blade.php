@extends('layout.auth')

@section('form')
    <form action="{{ route('checkLogin') }}" method="POST">
        <h1>Login Form</h1>
        @if (session('message2'))
            <p style="color: DodgerBlue">{{ session('message2') }}</p>
        @endif
        <div>
            <input type="text" class="form-control" name="email"
                value="@if (session('email')) {{ session('email') }} @else{{ $_COOKIE['email'] ?? '' }}@endif" placeholder="Email" />
        </div>
        <div>
            <input type="password" class="form-control" name="password" value="{{ $_COOKIE['password'] ?? '' }}" placeholder="Password" />
            @if (session('message'))
                <p style="color: red">{{ session('message') }}</p>
            @endif
        </div>
            <input class="form-check-input" type="checkbox" name="remember" id="remember" @if (!empty($_COOKIE['email'])) checked @endif>

            <label class="form-check-label " for="remember">
                Remember Me
            </label>
        <div>
            @csrf
            <button type="submit" class="btn btn-default submit">Log in</button>
            <a class="reset_pass" href="{{ route('lostPass') }}">Lost your password?</a>
        </div>

        <div class="clearfix"></div>

        <div class="separator">
            <a href="{{ route('login.Google') }}" class="to_register"> Login Google </a>
            <p class="change_link">New to site?
                <a href="{{ route('signup') }}" class="to_register"> Create Account </a>
            </p>

            <div class="clearfix"></div>
    </form>
@endsection
