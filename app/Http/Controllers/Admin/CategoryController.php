<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Course;
use App\Http\Requests\CategoryRequest;

use function PHPUnit\Framework\isNull;

class CategoryController extends Controller
{
    public function list(Request $request){
        $search = $request->search;
        // dd($search);
        $collum = $request->sort;

        $order = $request->order;
        // dd($order);
        $data = Category::where('name', 'LIKE', '%' . $search . '%')
                  ->orWhere('id', $search)
                  ->orderBy($collum ?? 'updated_at', $order ?? 'desc')
                  ->paginate(20);
        // dd($data[0]->id);

        // Đổi phương thức sắp xếp liên tục sau mỗi lần click sắp xếp
        $order = $order == 'asc' ? 'desc' : 'asc';
        return view('admin.web.categories.List', compact('data', 'search', 'order'));
    }

    public function delete($id){
        // Kiểm tra xem category có được sử dụng hay không
        $select = Course::where('category_id', $id)->get()->toArray();
        // dd($select);
        if (empty($select)) {
            // chạy câu lệnh soft delete 
            $delete = Category::destroy($id);
            // dd($delete);
            return redirect(route('categories.list'))->with('message', 'Xóa thành công');
        } else {
            return back()->with('message2', 'Không thể xóa vì category này đã được sử dụng bên courses');
        }
        
    }

    public function formAdd(){
        return view('admin.web.categories.Add');
    }

    public function addData(CategoryRequest $request)
    {

        $name = $request->name;
        $order = $request->order;

        $add = new Category;
        $add->name = $name;
        $add->order = $order;
        $add->save();
        return redirect(route('categories.list'))->with('message', 'Thêm sản phẩm thành công');
    }

    public function formEdit($id){

        $categoryEdit = Category::find($id);
        // dd($categoryEdit);
        if(!$categoryEdit){
            return back();
        }
        return view('admin.web.categories.Edit', compact('categoryEdit', 'id'));
    }

    public function updateData($id, CategoryRequest $request)
    {
        $name = $request->name;
        $order = $request->order;

        $add =  Category::find($id);
        // dd($add);
        $add->name = $name;
        $add->order = $order;
        $add->save();
        return redirect(route('categories.list'))->with('message', 'Sửa sản phẩm thành công');
    }
}
