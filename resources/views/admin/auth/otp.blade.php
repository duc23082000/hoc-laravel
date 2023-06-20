@extends('layout.auth')

@section('form')
    <form action="" method="POST">
        <h1>Two-factor</h1>
        <div>
            <label for="">Vui lòng kiểm tra mail và nhập mã otp 6 số được gửi về email của bạn</label>
            <input type="text" class="form-control" name="otp" value="" placeholder="Otp" />
        </div>
        @if (session('message'))
            <p style="color: red">{{ session('message') }}</p>
        @endif
        @csrf
        <div>
            <button type="submit" class="btn btn-default submit">Submit</button>
        </div>
        <div class="clearfix"></div>
        <a class="reset_pass" href="{{ route('logout') }}">Sign in with another account</a>
        
        <div class="separator">
            

        <div class="clearfix"></div>

    </form>
@endsection
