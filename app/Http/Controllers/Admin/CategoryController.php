<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Requests\CategoryRequest;

use function PHPUnit\Framework\isNull;

class CategoryController extends Controller
{
    public function list(Request $request){
        $search = $request->search;
        // dd($search);
        // $categories = Category::onlyTrashed()->get();
        // dd($categories);
        $list = collect(Category::where('name', 'LIKE', '%' . $search . '%')
                  ->orWhere('id', $search)->paginate(20))->toArray();
        $link = Category::where('name', 'LIKE', '%' . $search . '%')
                  ->orWhere('id', $search)->paginate(20);
        return view('client.web.categories.categoriesList', compact('list', 'link', 'search'));
    }

    public function delete($id){
        $delete = Category::find($id);
        $delete->delete();
        // dd($delete);
        return redirect(route('categories.list'))->with('message', 'Xóa thành công');
    }

    public function formAdd(){
        return view('client.web.categories.categoriesAdd');
    }

    public function addData(CategoryRequest $request){

        $name = $request->name;
        $order = $request->order;
        $select =  collect(Category::where('name', $name)->get())->toArray();
        // dd($select);
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
        $request->session()->put('id', $id);
        $categoryEdit = collect(Category::find($id))->toArray();
        // dd($categoryEdit);
        if(!empty($categoryEdit)){
            return view('client.web.categories.categoriesEdit', compact('categoryEdit'));
        } else {
            return back();
        }
    }

    public function updateData(CategoryRequest $request){

        $id = session('id');
        // dd($id);
        $name = $request->name;
        $order = $request->order;
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
