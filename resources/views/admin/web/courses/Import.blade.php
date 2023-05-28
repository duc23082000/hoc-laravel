@extends('layout.app')

@section('content')
<div class="right_col" role="main">
    <form action="{{ route('import.excel') }}" method="post" enctype="multipart/form-data">
        {{-- File excel  --}}
        <div class="mb-3">
            <label for="image">Excel:</label>
            <input class="form-control form-control"  type="file" name="excel" id="excel">
        </div>
        @error('excel')
            <p style="color: red">{{ $message }}</p>
        @enderror

        {{-- Submit dữ liệu  --}}
        @csrf
        <button type="submit" class="btn btn-primary">Import</button>
        <a href="{{ route('courses.list') }}" class="btn btn-secondary">Thoát</a>
    </form>
    @if(session("datas"))
    <div class="alert alert-danger" align="center">
            @foreach(session('datas') as $data)
              @foreach ($data as $eachData)
                <p>{{ $eachData }}</p>
              @endforeach
            @endforeach
    </div>
  @endif
</div>
@endsection

