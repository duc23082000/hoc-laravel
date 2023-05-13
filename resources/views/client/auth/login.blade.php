@extends('layout.auth')

@section('form')
<form action="{{ route('checkLogin') }}" method="POST">
    <h1>Login Form</h1>
    @if (session('message2'))
      <p style="color: DodgerBlue">{{ session('message2') }}</p>
    @endif
    <div>
      <input type="text" class="form-control" 
      name="email" value="@if(session('email')) {{ session('email') }} @endif"
      placeholder="Email"/>
    </div>
    <div>
      <input type="password" class="form-control" 
      name="password"
      placeholder="Password"/>
      @if (session('message'))
        <p style="color: red">{{ session('message') }}</p>
      @endif
    </div>
    <div>
      @csrf
      <button type="submit" class="btn btn-default submit">Log in</button>
      <a class="reset_pass" href="{{ route('lostPass') }}">Lost your password?</a>
    </div>

    <div class="clearfix"></div>

    <div class="separator">
      <p class="change_link">New to site?
        <a href="{{ route('signup') }}" class="to_register"> Create Account </a>
      </p>

      <div class="clearfix"></div>
</form>
@endsection