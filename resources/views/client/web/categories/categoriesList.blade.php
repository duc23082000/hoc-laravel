@extends('layout.app')

@section('css')
  <style>
    .input{
      width: 20%;
      display: inline;
      margin-bottom: 30px
    }
    .alert2{
      text-align: center;
      align-items: center;
      width: 50%;
    }
  </style>
@endsection

@section('content')
<div class="right_col" role="main">

  <form action="" method="GET">
      <input class="form-control mr-sm-2 input" type="search" 
      name="search" value="{{ $search }}"
      placeholder="Search" aria-label="Search">
      <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
  </form>

  <a href="{{ route('categories.add') }}" class="btn btn-outline-success">Thêm</a>
  
  @if (session('message'))
      <p class="alert alert-primary" style="text-align: center">{{ session('message') }}</p>
  @endif
  <table class="table table-bordered">
      <thead>
        <tr>
          <th scope="col">Id</th>
          <th scope="col">Name</th>
          <th scope="col">Order</th>
          <th scope="col">Created at</th>
          <th scope="col">Modified at</th>
          <th scope="col"></th>
        </tr>
      </thead>
      <tbody>
        @foreach ($list['data'] as $key => $item)
            <tr>
              <td>{{ $item['id'] }}</td>
              <td>{{ $item['name'] }}</td>
              <td>{{ $item['order'] }}</td>
              <td>{{ $item['created_at'] }}</td>
              <td>{{ $item['updated_at'] }}</td>
              <td>
                <a href="{{ route('categories.edit', ['id'=>$item['id'], 'name'=>$item['name'], 'order'=>$item['order']]) }}"
                class="btn btn-warning btn-sm">Sửa</a>
                <a href="{{ route('categories.delete', ['id'=>$item['id']]) }}"
                class="btn btn-danger btn-sm"
                onclick="return confirm('Bạn có chắc chắn muốn xóa?')" >Xóa</a>
              </td>
            </tr>
        @endforeach
      </tbody>
  </table>
  {{ $link->links('vendor.pagination.bootstrap-5') }}
</div>
@endsection

@section('script')
<script>
  function confirmDelete() {
      return confirm("Bạn có chắc muốn xóa bản ghi này?");
  }
</script>
@endsection


