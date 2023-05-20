<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CourseRequest;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;

class Course2Controller extends Controller
{
    public function list(Request $request) {
        // thông tin tìm kiếm
        $search = $request->search;

        // cột sắp xếp
        $collum = $request->sort;

        // phương thức săp xếp
        $order = $request->order;
        // dd($search);

        // Sử dụng Query Builder
        $joinResult = DB::table('courses')
        ->join('categories', 'courses.category_id', '=', 'categories.id')
        ->select('courses.id', 'courses.course_name', 'courses.price', 'courses.created_at', 'courses.updated_at', 'categories.name')
        ->where(function ($query) use ($search) {
            $query->where('courses.id', $search)
                ->orWhere('courses.course_name', 'LIKE', '%' . $search . '%')
                ->orWhere('categories.name', 'LIKE', "%$search%");
        })
        ->whereNull('courses.deleted_at')
        ->orderBy($collum ?? 'courses.updated_at', $order ?? 'desc')
        ->paginate(20);
        

        // Dữ liệu bảng 
        // dd($joinResult->all());
        $dataJoin = $joinResult->items();
        // dd($dataJoin[0]->id);
        
        // Đổi phương thức sắp xếp liên tục sau mỗi lần click sắp xếp
        $order = $order == 'asc' ? 'desc' : 'asc';
        return view('admin.web.courses.List', compact('search', 'joinResult', 'dataJoin', 'order'));
    }

    public function show($id){
        // Join bảng để lấy dữ liệu thông tin khóa học
        $joinCategories = DB::table('courses')
        ->join('categories', 'courses.category_id', '=', 'categories.id')
        ->where('courses.id', $id)
        ->whereNull('courses.deleted_at')->get();
        // dd($joinCategories);

        // Join bảng để lấy dữ thông tin người tạo khóa học 
        $joinUserCreate = DB::table('courses')
        ->join('users', 'courses.created_by_id', '=', 'users.id')
        ->where('courses.id', $id)
        ->whereNull('courses.deleted_at')->get();

        // Join bảng để lấy dữ thông tin người sửa khóa học 
        $joinUserModify = DB::table('courses')
        ->join('users', 'courses.modified_by_id', '=', 'users.id')
        ->where('courses.id', $id)
        ->whereNull('courses.deleted_at')->get();

        return view('admin.web.courses.Show',compact('joinCategories', 'joinUserCreate', 'joinUserModify'));
    }

    public function delete($id){
        // Add deleted_at = now
        $delete = DB::table('courses')->where('id', $id)
        ->update([
            'deleted_at'=>now()->format('Y-m-d H:i:s'), 
            'updated_at' => now()->format('Y-m-d H:i:s')
        ]);

        // dd($delete);
        return redirect(route('courses.list'))->with('message', 'Xóa thành công');
    }

    public function formAdd(){
        // Lấy ra danh sách category để điền vào thẻ select
        $categorylist = DB::table('categories')
        ->whereNull('deleted_at')->get();
        // dd($categorylist[0]->id);

        return view('admin.web.courses.Add', compact('categorylist'));
    }

    public function addData(CourseRequest $request){

        // kiểm tra xem người dùng có gửi ảnh hay ko nếu có thì thêm ảnh ngược lại ảnh sẽ là null 
        if (!empty($request->image)) {
            $image = $request->image;

            // Lấy ra định dạng của file 
            $format = $image->getClientOriginalExtension();
            // Lấy ra kích thước của file
            $size = $image->getSize();

            // Kiểm tra kiểu dữ liệu và kích thước file nếu là ảnh thì thực hiện lưu còn ko thì thoát
            if (($format == 'jpg' || $format == 'png' || $format == 'gif') && $size < 26214400) {
                // dd('haha');
                $fileName = Str::random(40) . '.' . $image->getClientOriginalExtension();
                // dd($fileName);
                $path = $image->storeAs('images', $fileName, 'public');
                $create = DB::table('courses')
                ->insert([
                    'course_name' => $request->name,
                    'price' => $request->price,
                    'category_id' => $request->category,
                    'description' => $request->description,
                    'image' => $fileName,
                    'created_by_id' => Auth::user()->id,
                    'modified_by_id' => Auth::user()->id,
                    'created_at' => now()->format('Y-m-d H:i:s'),
                    'updated_at' => now()->format('Y-m-d H:i:s')
                ]);

                return redirect(route('courses.list'))->with('message', 'Thêm khóa học thành công');
            } else {
                return back()->with(['message' => 'Ảnh phải có định dạng jpg, png, gif và phải có kích thước nhỏ hơn 25MB',
                                    'name' => $request->name,
                                    'price' => $request->price,
                                    'description' => $request->description]);
            }

            
        } else {
            $create = DB::table('courses')
            ->insert([
                'course_name' => $request->name,
                'price' => $request->price,
                'category_id' => $request->category,
                'description' => $request->description,
                'created_by_id' => Auth::user()->id,
                'modified_by_id' => Auth::user()->id,
                'created_at' => now()->format('Y-m-d H:i:s'),
                'updated_at' => now()->format('Y-m-d H:i:s')
            ]);
            return redirect(route('courses.list'))->with('message', 'Thêm khóa học thành công');
        }
    }

    public function formEdit($id, Request $request){
        // Lấy ra danh sách category để điền vào thẻ select
        $categorylist = DB::table('categories')
        ->whereNull('deleted_at')->get();
        // dd($categorylist);

        // Lấy ra thông tin bản ghi course cần sửa
        $courseList = DB::table('courses')
        ->where('id', $id)
        ->whereNull('deleted_at')
        ->first();
        // dd($courseList->category_id);

        // tạo biến id session 
        $request->session()->put('id', $id);

        // Kiểm tra xem id có tồn tại hay ko phòng trường hợp người dùng đổi id trên url
        if(!empty($courseList)){
            $category = DB::table('categories')
            ->where('id', $courseList->category_id)->first()->name;
            // dd($category);
            return view('admin.web.courses.Edit', compact('categorylist', 'courseList', 'category'));
        } else {
            return back();
        }
    }

    public function updateData(CourseRequest $request){
        // Láy ra id của course cần sửa thông biến session 
        $id = session('id');
        // dd($id);

        // kiểm tra xem người dùng có sửa ảnh hay không nếu có thì xóa ảnh cũ và thêm ảnh mới 
        if (!empty($request->image)) {
            $image = $request->image;

            // Lấy ra định dạng của file 
            $format = $image->getClientOriginalExtension();
            // Lấy ra kích thước của file
            $size = $image->getSize();

            // Kiểm tra kiểu dữ liệu và kích thước file nếu là ảnh thì thực hiện lưu còn ko thì thoát
            if (($format == 'jpg' || $format == 'png' || $format == 'gif') && $size < 26214400) {

                $image_old = DB::table('courses')
                ->where('id', $id)->first()->image;
                // dd($image_old);

                // Kiểm tra xem có ảnh cũ hay không nếu có thì xóa file cũ và thêm file mới còn không thì thêm luôn file mơis
                if (!empty($image_old)) {
                    // Xóa file cũ
                    File::delete(storage_path('app/public/images/' . $image_old));

                    // Đổi tên và lưu file mới
                    $fileName = Str::random(40) . '.' . $image->getClientOriginalExtension();
                    // dd($fileName);
                    $path = $image->storeAs('images', $fileName, 'public');

                    // Sửa dữ liệu
                    $create = DB::table('courses')
                    ->where('id', $id)
                    ->update([
                        'course_name' => $request->name,
                        'price' => $request->price,
                        'category_id' => $request->category,
                        'description' => $request->description,
                        'image' => $fileName,
                        'created_by_id' => Auth::user()->id,
                        'modified_by_id' => Auth::user()->id,
                        'updated_at' => now()->format('Y-m-d H:i:s')
                    ]);

                    return redirect(route('courses.list'))->with('message', 'Sửa khóa học thành công');
                } else {
                    // Đổi tên và lưu ảnh 
                    $fileName = Str::random(40) . '.' . $image->getClientOriginalExtension();
                    // dd($fileName);
                    $path = $image->storeAs('images', $fileName, 'public');

                    // Sửa dữ liệu
                    $create = DB::table('courses')
                    ->where('id', $id)
                    ->update([
                        'course_name' => $request->name,
                        'price' => $request->price,
                        'category_id' => $request->category,
                        'description' => $request->description,
                        'image' => $fileName,
                        'created_by_id' => Auth::user()->id,
                        'modified_by_id' => Auth::user()->id,
                        'updated_at' => now()->format('Y-m-d H:i:s')
                    ]);
                    return redirect(route('courses.list'))->with('message', 'Sửa khóa học thành công');
                }

            } else {
                return back()->with(['message' => 'Ảnh phải có định dạng jpg, png, gif và phải có kích thước nhỏ hơn 25MB',
                                    'name' => $request->name,
                                    'price' => $request->price,
                                    'description' => $request->description]);
            }

        } else {
            // Sửa dữ liệu
            $create = DB::table('courses')
            ->where('id', $id)
            ->update([
                'course_name' => $request->name,
                'price' => $request->price,
                'category_id' => $request->category,
                'description' => $request->description,
                'created_by_id' => Auth::user()->id,
                'modified_by_id' => Auth::user()->id,
                'updated_at' => now()->format('Y-m-d H:i:s')
            ]);
            return redirect(route('courses.list'))->with('message', 'Sửa khóa học thành công');
        }
    }
}
