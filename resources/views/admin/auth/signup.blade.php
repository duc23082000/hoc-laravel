@extends('layout.auth')

@section('form')
<form action="{{ route('createAccount') }}" method="POST">
    <h1>Create Account</h1>
    <div>
        <input type="text" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email" />
        @error('email')
        <p style="color: red">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <input type="password" class="form-control" name="password" value="{{ old('password') }}" placeholder="Password" />
        @error('password')
        <p style="color: red">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <input type="password" class="form-control" name="cfpassword" value="{{ old('cfpassword') }}" placeholder="Comfirm Password" />
    </div>

    @csrf
    <div>
        <button type="submit" class="btn btn-default submit">Submit</button>
    </div>
    <div class="clearfix"></div>

    <div class="separator">
        <p class="change_link">Already a member ?
            <a href="{{ route('login') }}" class="to_register"> Log in </a>
        </p>

        <div class="clearfix"></div>

</form>
@endsection