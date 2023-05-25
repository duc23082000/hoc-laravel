@extends('layout.app')

@section('css')
  <style>
    .button-sorf {
        border: none;
        background-color: transparent;
        float: right;
        outline: none;
    }
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
  @if (session('message2'))
      <p class="alert alert-danger" style="text-align: center">{{ session('message2') }}</p>
  @endif
  <table class="table table-bordered">
      <thead>
        <tr>
          <th scope="col">
            <form action="" method="get">
              Id
              <input type="hidden" name="sort" value="id">
              <input type="hidden" name="order" value="{{ $order ?? 'asc' }}">
              <input type="hidden" name="search" value="{{ $search }}">
              <button class="button-sorf"><i class="fa fa-sort" aria-hidden="true"></i></button>
            </form>
          </th>
          <th scope="col">
            <form action="" method="get">
              Name
              <input type="hidden" name="sort" value="name">
              <input type="hidden" name="order" value="{{ $order ?? 'asc' }}">
              <input type="hidden" name="search" value="{{ $search }}">
              <button class="button-sorf"><i class="fa fa-sort" aria-hidden="true"></i></button>
            </form>
          </th>
          <th scope="col">
            <form action="" method="get">
              Order
              <input type="hidden" name="sort" value="order">
              <input type="hidden" name="order" value="{{ $order ?? 'asc' }}">
              <input type="hidden" name="search" value="{{ $search }}">
              <button class="button-sorf"><i class="fa fa-sort" aria-hidden="true"></i></button>
            </form>
          </th>
          <th scope="col">
            <form action="" method="get">
              Created at
              <input type="hidden" name="sort" value="created_at">
              <input type="hidden" name="order" value="{{ $order ?? 'asc' }}">
              <input type="hidden" name="search" value="{{ $search }}">
              <button class="button-sorf"><i class="fa fa-sort" aria-hidden="true"></i></button>
            </form>
          </th>
          <th scope="col">
            <form action="" method="get">
              Modified at
              <input type="hidden" name="sort" value="updated_at">
              <input type="hidden" name="order" value="{{ $order ?? 'asc' }}">
              <input type="hidden" name="search" value="{{ $search }}">
              <button class="button-sorf"><i class="fa fa-sort" aria-hidden="true"></i></button>
            </form>
          </th>
          <th scope="col"></th>
        </tr>
      </thead>
      <tbody>
        @foreach ($data as $item)
            <tr>
              <td>{{ $item->id }}</td>
              <td>{{ $item->name }}</td>
              <td>{{ $item->order }}</td>
              <td>{{ $item->created_at }}</td>
              <td>{{ $item->updated_at }}</td>
              <td>
                <a href="{{ route('categories.edit', ['id'=>$item['id']]) }}"
                class="btn btn-warning btn-sm">Sửa</a>
                <a href="{{ route('categories.delete', ['id'=>$item['id']]) }}"
                class="btn btn-danger btn-sm"
                onclick="return confirm('Bạn có chắc chắn muốn xóa?')" >Xóa</a>
              </td>
            </tr>
        @endforeach
      </tbody>
  </table>
  {{ $data->links('vendor.pagination.bootstrap-5') }}
</div>
@endsection

@section('script')
<script>
  function confirmDelete() {
      return confirm("Bạn có chắc muốn xóa bản ghi này?");
  }
</script>
@endsection


