@extends('layout.auth')

@section('form')
    <form action="{{ route('change') }}" method="POST">
        <h1>Change Password</h1>
        <div>
            <input type="password" class="form-control" name="password" value="{{ old('password') }}" placeholder="Password" />
            @error('password')
                <p style="color: red">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <input type="password" class="form-control" name="cfpassword" value="{{ old('cfpassword') }}"
                placeholder="Comfirm Password" />
        </div>
        @csrf
        <div>
            <button type="submit" class="btn btn-default submit">Submit</button>
        </div>
        <div class="clearfix"></div>
        <div class="separator">

            <div class="clearfix"></div>

    </form>
@endsection
