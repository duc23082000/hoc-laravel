@extends('layout.app')

@section('content')
    <div class="right_col" role="main">
        <form action="{{ route('import.lesson') }}" method="post" enctype="multipart/form-data">
            {{-- File excel  --}}
            <div class="mb-3">
                <label for="excel">Excel:</label>
                <input class="form-control form-control" type="file" name="excel[]" id="excel" multiple>
            </div>
            @foreach ($errors->all() as $key => $error)
                <p style="color: red">File {{ $key + 1 }}: {{ $error }}</p>
            @endforeach
            @if (session('message'))
                <p style="color: red">{{ session('message') }}</p>
            @endif

            {{-- Submit dữ liệu  --}}
            @csrf
            <button type="submit" class="btn btn-primary">Import</button>
            <a href="{{ route('courses.list') }}" class="btn btn-secondary">@lang('content.back')</a>
        </form>
        @foreach ($notication as $item)
        <div class="alert alert-{{ $item->status_color }} w-75" onclick="location.href='{{ route('show.error', [$item->id]) }}'" style="cursor: pointer;">
            <a href="{{ route('show.error', [$item->id]) }}" >File {{ $item->name }} {{ $item->status_name }}</a>
            <p style="text-align:right; display: inline-block; float: right;">{{ $item->created_at }}</p>
        </div>
        @endforeach
        <a href="{{ route('remote.error') }}" class="btn btn-danger">Delete Notification</a>
        {{$notication->links('vendor.pagination.bootstrap-5')}}
    </div>
@endsection