<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Course;
use App\Http\Requests\CategoryRequest;
use App\Http\Requests\ListRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isNull;

class CategoryController extends Controller
{
    public function list(ListRequest $request){
        $search = $request->search;
        // dd($search);
        $collum = $request->sort;

        $order = $request->order;
        // dd($request->url());
        // dd($order);
        // $data = Category::where('name', 'LIKE', '%' . $search . '%')
        //           ->orWhere('id', $search)
        //           ->orderBy($collum ?? 'updated_at', $order ?? 'desc')
        //           ->paginate(20);

        if (empty($request->all()) || $request->all() == ['page' => 1] ||  $request->all() == ['page' => null]) {
            $data = Cache::remember('joinData', now()->addMinute(10), function () {
                return Category::leftJoin('courses', 'categories.id', '=', 'courses.category_id')
                ->select('categories.id', 'categories.name', DB::raw('COUNT(courses.category_id) as orders'), 'categories.created_at', 'categories.updated_at')
                ->groupBy('categories.id', 'categories.name', 'categories.created_at', 'categories.updated_at')
                ->paginate(20);
            });
            return view('admin.web.categories.List', compact('data', 'search', 'order'));
        }

        $data = Category::leftJoin('courses', 'categories.id', '=', 'courses.category_id')
            ->select('categories.id', 'categories.name', DB::raw('COUNT(courses.category_id) as orders'), 'categories.created_at', 'categories.updated_at')
            ->where(function ($query) use($search){
                $query->where('categories.name', 'LIKE', '%' . $search . '%')
                ->orWhere('categories.id', $search);
            })
            ->whereNull('courses.deleted_at')
            ->groupBy('categories.id', 'categories.name', 'categories.created_at', 'categories.updated_at')
            ->orderBy($collum ?? 'orders', $order ?? 'desc')
            ->paginate(20);
        
        // dd($data);


        // Đổi phương thức sắp xếp liên tục sau mỗi lần click sắp xếp
        $order = $order == 'asc' ? 'desc' : 'asc';
        return view('admin.web.categories.List', compact('data', 'search', 'order'));
    }

    public function delete($id){
        // Kiểm tra xem category có được sử dụng hay không
        $select = Course::where('category_id', $id)->first();
        // dd(!$select);
        if (empty($select)) {
            // chạy câu lệnh soft delete 
            $delete = Category::destroy($id);
            // dd($delete);
            if($delete){
                Cache::forget('joinData');
                return redirect(route('categories.list'))->with('message', 'Xóa thành công');
            }
            return redirect(route('categories.list'))->with('message2', 'Category Không tồn tại');
        }
        
        return back()->with('message2', 'Không thể xóa vì category này đã được sử dụng bên courses');
        
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
        Cache::forget('joinData');
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
        Cache::forget('joinData');
        return redirect(route('categories.list'))->with('message', 'Sửa sản phẩm thành công');
    }
}
