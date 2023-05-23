<table class="table table-bordered">
    <thead>
      <tr>
          <th scope="col" style="width: 5%">
            Id
          </th>
          <th scope="col" style="width: 30%">
              Name
          </th>
          <th scope="col" style="width: 10%">
              Price
          </th>
          <th scope="col" style="width: 15%">
              Category
          </th>
          <th scope="col" style="width: 15%">
              Created at
          </th>
          <th scope="col" style="width: 15%">
              Modified at
          </th>
      </tr>
    </thead>
    <tbody>
      @foreach ($dataJoin as $item)
          <tr>
            <td>
              {{ $item->id }}
            </td>
            <td>
                {{ $item->course_name }}
            </td>
            <td>
              {{ $item->price }}$
            </td>
            <td>
              {{ $item->name }}
            </td>
            <td>
              {{ $item->created_at }}
            </td>
            <td>
              {{ $item->updated_at }}
            </td>
          </tr>
      @endforeach
    </tbody>
</table>