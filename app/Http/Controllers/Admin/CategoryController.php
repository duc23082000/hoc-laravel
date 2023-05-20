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
        $sql = Category::where('name', 'LIKE', '%' . $search . '%')
                  ->orWhere('id', $search)
                  ->orderBy($collum ?? 'updated_at', $order ?? 'desc')
                  ->paginate(20);
        // dd($sql);

        $data = $sql->items();
        // dd($data[0]->id);

        // Đổi phương thức sắp xếp liên tục sau mỗi lần click sắp xếp
        $order = $order == 'asc' ? 'desc' : 'asc';
        return view('admin.web.categories.List', compact('data', 'sql', 'search', 'order'));
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

    public function addData(CategoryRequest $request){

        $name = $request->name;
        $order = $request->order;

        // Kiểm tra xem tên đã có hay chưa
        $select =  Category::where('name', $name)->get()->toArray();
        // $a = Category::where('name', $name)->get();
        // dd($a[0]->id);
        if(empty($select)){
            $add = new Category;
            $add->name = $name;
            $add->order = $order;
            $add->save();
            return redirect(route('categories.list'))->with('message', 'Thêm sản phẩm thành công');
        } else {
            return back()->with(['message2'=>'Sản phẩm đã tồn tại', 'name'=>$name, 'order'=>$order]);
        }
    }

    public function formEdit($id, Request $request){
        // tạo biến id thông qua session 
        $request->session()->put('id', $id);

        // Kiểm tra xem id đc truyền vào thông qua url có tồn tại hay không
        $categoryEdit = collect(Category::find($id))->toArray();
        // dd($categoryEdit);
        if(!empty($categoryEdit)){
            return view('admin.web.categories.Edit', compact('categoryEdit'));
        } else {
            return back();
        }
    }

    public function updateData(CategoryRequest $request){
        // lấy ra biến id được tạo thông qua session 
        $id = session('id');
        // dd($id);
        $name = $request->name;
        $order = $request->order;

        // kiểm tra xem tên có hay chưa
        $select =  collect(Category::where('name', $name)->where('id', '!=', $id)->get())->toArray();
        // dd($select);
        if(empty($select)){
            $add =  Category::find($id);
            // dd($add);
            $add->name = $name;
            $add->order = $order;
            $add->save();
            return redirect(route('categories.list'))->with('message', 'Sửa sản phẩm thành công');
        } else {
            return back()->with(['message2'=>'Sản phẩm đã tồn tại', 'name'=>$name, 'order'=>$order]);
        }
    }
}
