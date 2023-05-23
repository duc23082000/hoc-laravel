<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CourseRequest;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

use App\Models\Category;
use App\Models\UserModel;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function list(Request $request) {
        // thông tin tìm kiếm
        $search = $request->search;

        // cột sắp xếp
        $collum = $request->sort;

        // phương thức săp xếp
        $order = $request->order;
        // dd($search);
        
        // Sử dụng Eloquent
        $joinResult = Course::join('categories', 'courses.category_id', '=', 'categories.id')
        ->select('courses.id', 'courses.course_name', 'courses.price', 'courses.created_at', 'courses.updated_at', 'categories.name')
        ->where(function ($query) use ($search) {
            $query->where('courses.id', $search)
                ->orWhere('courses.course_name', 'LIKE', '%' . $search . '%')
                ->orWhere('categories.name', 'LIKE', "%$search%");
        })
        ->orderBy($collum ?? 'courses.updated_at', $order ?? 'desc')
        ->paginate(20);

        // Dữ liệu bảng
        // dd($joinResult);
        $dataJoin = $joinResult->items();
        // dd($dataJoin);
        Log::info($dataJoin);
        
        // Đổi phương thức sắp xếp liên tục sau mỗi lần click sắp xếp
        $order = $order == 'asc' ? 'desc' : 'asc';
        return view('admin.web.courses.List', compact('search', 'joinResult', 'dataJoin', 'order'));
    }

    public function show($id){
        // Lấy ra dữ liệu khóa học
        $data = Course::find($id);

        return view('admin.web.courses.Show',compact('data'));
    }

    public function delete($id){
        // Chạy câu lệnh soft delete
        $delete = Course::destroy($id);
        // hoặc
        // $delete = Course::find($id)->delete();

        // dd($delete);
        return redirect(route('courses.list'))->with('message', 'Xóa thành công');
    }

    public function formAdd(){
        // Lấy ra danh sách category để điền vào thẻ select
        $categorylist = Category::all();
        // dd($categorylist[0]->id);

        return view('admin.web.courses.Add', compact('categorylist'));
    }

    public function addData(CourseRequest $request){
        // dd('haha');

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
                // Tạo ra 1 khóa học mới 
                $course = new Course();
                $course->image = $fileName;
                $course->course_name = $request->name;
                $course->price = $request->price;
                $course->category_id = $request->category;
                $course->description = $request->description;
                // dd(Auth::user()->id);
                $course->created_by_id = Auth::user()->id;
                $course->modified_by_id = Auth::user()->id;
                $course->save();

                return redirect(route('courses.list'))->with('message', 'Thêm khóa học thành công');
            } else {
                return back()->with(['message' => 'Ảnh phải có định dạng jpg, png, gif và phải có kích thước nhỏ hơn 25MB',
                                    'name' => $request->name,
                                    'price' => $request->price,
                                    'description' => $request->description]);
            }

            
        } else {
            // Tạo ra 1 khóa học mới 
            $course = new Course();
            $course->course_name = $request->name;
            $course->price = $request->price;
            $course->category_id = $request->category;
            $course->description = $request->description;
            // dd(Auth::user()->id);
            $course->created_by_id = Auth::user()->id;
            $course->modified_by_id = Auth::user()->id;
            $course->save();
            return redirect(route('courses.list'))->with('message', 'Thêm khóa học thành công');
        }
    }
    

    public function formEdit($id, Request $request){
        // Lấy ra danh sách category để điền vào thẻ select
        $categorylist = Category::all();
        // dd($categorylist);

        // Lấy ra thông tin bản ghi course cần sửa
        $course = Course::find($id);
        // dd($course->category->name);

        // tạo biến id session 
        $request->session()->put('id', $id);

        // Kiểm tra xem id có tồn tại hay ko phòng trường hợp người dùng đổi id trên url
        if(!empty($course)){
            return view('admin.web.courses.Edit', compact('categorylist', 'course'));
        } else {
            return back();
        }
    }

    public function updateData(CourseRequest $request){
        // Láy ra id của course cần sửa thông biến session 
        $id = session('id');
        // dd($id);

        // Tìm kiếm bản ghi cần sửa
        $course = Course::find($id);

        // kiểm tra xem người dùng có sửa ảnh hay không nếu có thì xóa ảnh cũ và thêm ảnh mới 
        if (!empty($request->image)) {
            $image = $request->image;

            // Lấy ra định dạng của file 
            $format = $image->getClientOriginalExtension();
            // Lấy ra kích thước của file
            $size = $image->getSize();

            // Kiểm tra kiểu dữ liệu và kích thước file nếu là ảnh thì thực hiện lưu còn ko thì thoát
            if (($format == 'jpg' || $format == 'png' || $format == 'gif') && $size < 26214400) {

                $image_old = Course::find($id)->image;
                // dd($image_old);

                // Kiểm tra xem có ảnh cũ hay không nếu có thì xóa file cũ và thêm file mới còn không thì thêm luôn file mơis
                if (!empty($image_old)) {
                    // Xóa file cũ
                    File::delete(storage_path('app/public/images/' . $image_old));

                    // Đổi tên và lưu file mới
                    $fileName = Str::random(40) . '.' . $image->getClientOriginalExtension();
                    // dd($fileName);
                    $path = $image->storeAs('images', $fileName, 'public');

                    $course->image = $fileName;
                    $course->course_name = $request->name;
                    $course->price = $request->price;
                    $course->category_id = $request->category;
                    $course->description = $request->description;
                    // dd(Auth::user()->id);
                    $course->modified_by_id = Auth::user()->id;
                    $course->save();

                    return redirect(route('courses.list'))->with('message', 'Sửa khóa học thành công');
                } else {
                    // Đổi tên và lưu ảnh 
                    $fileName = Str::random(40) . '.' . $image->getClientOriginalExtension();
                    // dd($fileName);
                    $path = $image->storeAs('images', $fileName, 'public');

                    $course->image = $fileName;
                    $course->course_name = $request->name;
                    $course->price = $request->price;
                    $course->category_id = $request->category;
                    $course->description = $request->description;
                    // dd(Auth::user()->id);
                    $course->modified_by_id = Auth::user()->id;
                    $course->save();

                    return redirect(route('courses.list'))->with('message', 'Sửa khóa học thành công');
                }

            } else {
                return back()->with(['message' => 'Ảnh phải có định dạng jpg, png, gif và phải có kích thước nhỏ hơn 25MB',
                                    'name' => $request->name,
                                    'price' => $request->price,
                                    'description' => $request->description]);
            }

        } else {
            $course->course_name = $request->name;
            $course->price = $request->price;
            $course->category_id = $request->category;
            $course->description = $request->description;
            // dd(Auth::user()->id);
            $course->modified_by_id = Auth::user()->id;
            $course->save();
            return redirect(route('courses.list'))->with('message', 'Sửa khóa học thành công');
        }
    }
}
