<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categories;

use function PHPUnit\Framework\isNull;

class CategoryController extends Controller
{
    public function list(Request $request){
        $username = explode("@", session('email'))[0];
        $search = $request->search;
        // dd($search);
        $list = collect(Categories::where(function ($query) use ($search) {
            $query->where('name', 'LIKE', '%' . $search . '%')
                  ->orWhere('id', $search);
        })->whereNull('deleted_at')->paginate(20))->toArray();
        $link = Categories::where(function ($query) use ($search) {
            $query->where('name', 'LIKE', '%' . $search . '%')
                  ->orWhere('id', $search);
        })->whereNull('deleted_at')->paginate(20);
        // dd($link);
        // $lists = $list['data'];
        // dd($list['data']);
        return view('client.web.categories.categoriesList', compact('username', 'list', 'link', 'search'));
    }

    public function delete($id){
        $delete = Categories::find($id);
        // dd(date('Y-m-d H:i:s'));
        $delete->deleted_at = date('Y-m-d H:i:s');
        $delete->save();
        // dd($delete);
        return redirect(route('categories.list'))->with('message', 'Xóa thành công');
    }

    public function formAdd(){
        $username = explode("@", session('email'))[0];
        return view('client.web.categories.categoriesAdd', compact('username'));
    }

    public function addData(Request $request){
        $request->validate([
            'name' => 'required',
            'order' => 'required|integer|gt:0'
        ], [
            'name.required' => 'Tên sản phẩm không được để trống',
            'order.required' => 'Số lượng không được để trống',
            'order.integer' => 'Số lượng phải là số nguyên',
            'order.gt' => 'Số lượng phải lớn hơn 0'
        ]);
        $name = $request->name;
        $order = $request->order;
        $select =  collect(Categories::where('name', $name)->get())->toArray();
        // dd($select);
        if(empty($select)){
            $add = new Categories;
            $add->name = $name;
            $add->order = $order;
            $add->save();
            return redirect(route('categories.list'))->with('message', 'Thêm sản phẩm thành công');
        } else {
            return back()->with(['message2'=>'Sản phẩm đã tồn tại', 'name'=>$name, 'order'=>$order]);
        }
    }

    public function formEdit($id, Request $request){
        $username = explode("@", session('email'))[0];
        $request->session()->put('id', $id);
        $categoryEdit = collect(Categories::find($id))->toArray();
        // dd($categoryEdit);
        if(!empty($categoryEdit)){
            return view('client.web.categories.categoriesEdit', compact('username', 'categoryEdit'));
        } else {
            return back();
        }
    }

    public function updateData(Request $request){
        $request->validate([
            'name' => 'required',
            'order' => 'required|integer|gt:0'
        ], [
            'name.required' => 'Tên sản phẩm không được để trống',
            'order.required' => 'Số lượng không được để trống',
            'order.integer' => 'Số lượng phải là số nguyên',
            'order.gt' => 'Số lượng phải lớn hơn 0'
        ]);
        $id = session('id');
        // dd($id);
        $name = $request->name;
        $order = $request->order;
        $add =  Categories::find($id);
        // dd($add);
        $add->name = $name;
        $add->order = $order;
        $add->save();
        return redirect(route('categories.list'))->with('message', 'Sửa sản phẩm thành công');
    }
}
