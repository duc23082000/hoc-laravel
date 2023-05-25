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

  <a href="{{ route('courses.add') }}" class="btn btn-outline-success">Thêm</a>
  <form action="{{ route('export.excel') }}" method="POST">
    <input type="hidden" name="sort" value="{{ $collum }}">
    <input type="hidden" name="order" value="{{ $orderExport }}">
    <input type="hidden" name="search" value="{{ $search }}">
    @csrf
    <button type="submit" class="btn btn-outline-success">Export Excel</button>
  </form>
  
  @if (session('message'))
      <p class="alert alert-primary" style="text-align: center">{{ session('message') }}</p>
  @endif
  <table class="table table-bordered">
      <thead>
        <tr>
            
            <th scope="col" style="width: 4%">
                <form action="" method="get">
                    Id
                    <input type="hidden" name="sort" value="courses.id">
                    <input type="hidden" name="order" value="{{ $order ?? 'asc' }}">
                    <input type="hidden" name="search" value="{{ $search }}">
                    <button class="button-sorf"><i class="fa fa-sort" aria-hidden="true"></i></button>
                </form>
            </th>
            <th scope="col" style="width: 20%">
              <form action="" method="get">
                Name
                <input type="hidden" name="sort" value="courses.course_name">
                <input type="hidden" name="order" value="{{ $order ?? 'asc' }}">
                <input type="hidden" name="search" value="{{ $search }}">
                <button class="button-sorf"><i class="fa fa-sort" aria-hidden="true"></i></button>
              </form>
            </th>
            <th scope="col" style="width: 5%">
              <form action="" method="get">
                Price
                <input type="hidden" name="sort" value="courses.price">
                <input type="hidden" name="order" value="{{ $order ?? 'asc' }}">
                <input type="hidden" name="search" value="{{ $search }}">
                <button class="button-sorf"><i class="fa fa-sort" aria-hidden="true"></i></button>
              </form>
            </th>
            <th scope="col" style="width: 5%">
              <form action="" method="get">
                Type
                <input type="hidden" name="sort" value="courses.price">
                <input type="hidden" name="order" value="{{ $order ?? 'asc' }}">
                <input type="hidden" name="search" value="{{ $search }}">
                <button class="button-sorf"><i class="fa fa-sort" aria-hidden="true"></i></button>
              </form>
            </th>
            <th scope="col" style="width: 15%">
              <form action="" method="get">
                Category
                <input type="hidden" name="sort" value="categories.name">
                <input type="hidden" name="order" value="{{ $order ?? 'asc' }}">
                <input type="hidden" name="search" value="{{ $search }}">
                <input type="hidden" name="case" value="1">
                <button class="button-sorf"><i class="fa fa-sort" aria-hidden="true"></i></button>
              </form>
            </th>
            <th scope="col" style="width: 15%">
              <form action="" method="get">               
                Created By
                <input type="hidden" name="sort" value="create_users.email">
                <input type="hidden" name="order" value="{{ $order ?? 'asc' }}">
                <input type="hidden" name="search" value="{{ $search }}">
                <input type="hidden" name="case" value="2">
                <button class="button-sorf"><i class="fa fa-sort" aria-hidden="true"></i></button>
              </form>
            </th>
            <th scope="col" style="width: 15%">
              <form action="" method="get">
                Modified By
                <input type="hidden" name="sort" value="email2">
                <input type="hidden" name="order" value="{{ $order ?? 'asc' }}">
                <input type="hidden" name="search" value="{{ $search }}">
                <input type="hidden" name="case" value="3">
                <button class="button-sorf"><i class="fa fa-sort" aria-hidden="true"></i></button>
              </form>
            </th>
            <th scope="col" style="width: 10%">
              <form action="" method="get">
                Created at
                <input type="hidden" name="sort" value="courses.created_at">
                <input type="hidden" name="order" value="{{ $order ?? 'asc' }}">         
                <input type="hidden" name="search" value="{{ $search }}">
                <button class="button-sorf"><i class="fa fa-sort" aria-hidden="true"></i></button>
              </form>
            </th>
            <th scope="col" style="width: 10%">
              <form action="" method="get">
                Modified at
                <input type="hidden" name="sort" value="courses.updated_at">
                <input type="hidden" name="order" value="{{ $order ?? 'asc' }}">
                <input type="hidden" name="search" value="{{ $search }}">
                <button class="button-sorf"><i class="fa fa-sort" aria-hidden="true"></i></button>
              </form>
            </th>
            <th scope="col"></th>
        </tr>
      </thead>
      <tbody>
        @foreach ($joinResult as $item)
            <tr>
              <td onclick="location.href='{{ route('courses.show', [$item->id]) }}'"
                  style="cursor: pointer;">
                {{ $item->id }}
              </td>
              <td onclick="location.href='{{ route('courses.show', [$item->id]) }}'"
                  style="cursor: pointer;">
                <a href="{{ route('courses.show', ['id'=>$item->id]) }}">
                  {{ $item->course_name }}
                </a>
              </td>
              <td onclick="location.href='{{ route('courses.show', ['id'=>$item->id]) }}'" 
                  style="cursor: pointer;">
                {{ $item->price }}$
              </td>
              <td onclick="location.href='{{ route('courses.show', ['id'=>$item->id]) }}'" 
                style="cursor: pointer;">
              {{ $item->fee_type }}
            </td>
              <td onclick="location.href='{{ route('courses.show', ['id'=>$item->id]) }}'" 
                  style="cursor: pointer;">
                {{ $item->category->name }}
              </td>
              <td onclick="location.href='{{ route('courses.show', ['id'=>$item->id]) }}'" 
                style="cursor: pointer;">
                {{ $item->user_create->email }}
              </td>
              <td onclick="location.href='{{ route('courses.show', ['id'=>$item->id]) }}'" 
                  style="cursor: pointer;">
                {{ $item->user_update->email }}
              </td>
              <td>
                {{ $item->created_at }}
              </td>
              <td>
                {{ $item->updated_at }}
              </td>
              <td>
                <a href="{{ route('courses.edit', ['id'=>$item->id]) }}"
                class="btn btn-warning btn-sm">Sửa</a>
                <a href="{{ route('courses.delete', ['id'=>$item->id]) }}"
                class="btn btn-danger btn-sm"
                onclick="return confirm('Bạn có chắc chắn muốn xóa?')" >Xóa</a>
              </td>
            </tr>
        @endforeach
      </tbody>
  </table>
  {{ $joinResult->links('vendor.pagination.bootstrap-5') }}
</div>
@endsection

@section('script')
<script>
  function confirmDelete() {
      return confirm("Bạn có chắc muốn xóa bản ghi này?");
  }
</script>
@endsection


