<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CourseRequest;
use App\Http\Requests\ImportRequest;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

use App\Models\Category;
use App\Models\UserModel;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;

use App\Exports\CoursesExport;
use App\Imports\ImportExcel;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


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

        $orderExport = $request->order;

        $case = $request->case;

        $joinResult = Course::with(['category', 'user_create', 'user_update'])
        ->where(function ($query) use ($search) {
            $query->where('courses.id', $search)
                ->orWhere('courses.course_name', 'LIKE', '%' . $search . '%')
                // ->orWhere('fee_type', 'LIKE', '%' . $search . '%')
                ->orWhereHas('category', function ($q) use ($search) {
                    $q->where('categories.name', 'LIKE', "%$search%");
                })
                ->orWhereHas('user_create', function ($q) use ($search) {
                    $q->where('users.email', 'LIKE', "%$search%");
                })
                ->orWhereHas('user_update', function ($q) use ($search) {
                    $q->where('users.email', 'LIKE', "%$search%");
                });
        })
        ->when(function ($query) use ($collum, $order, $case){
            switch ($case) {
                case '1':
                    return $query->orderByRaw("IFNULL((SELECT name FROM categories WHERE categories.id = courses.category_id), '') $order");
                case '2':
                    return $query->orderByRaw("IFNULL((SELECT id FROM users WHERE users.id = courses.created_by_id), '') $order");
                case '3':
                    return $query->orderByRaw("IFNULL((SELECT id FROM users WHERE users.id = courses.modified_by_id), '') $order");
                default:
                    return $query->orderBy($collum ?? 'courses.updated_at', $order ?? 'desc');
            }
        })
        ->paginate(20);
        // dd($joinResult);
        
        
        // Đổi phương thức sắp xếp liên tục sau mỗi lần click sắp xếp
        $order = $order == 'asc' ? 'desc' : 'asc';
        return view('admin.web.courses.List', compact('search', 'joinResult', 'order', 'collum', 'case', 'orderExport'));
    }

    public function show($id){
        // Lấy ra dữ liệu khóa học
        $data = Course::with(['user_update', 'user_create', 'category'])->find($id);
        // dd($data);

        if(!$data){
            return back();
        }

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

    public function addData(CourseRequest $request)
    {

        // Tạo ra 1 khóa học mới 
        $course = new Course();
        $course->course_name = $request->name;
        $course->price = $request->price;
        $course->category_id = $request->category;
        $course->description = $request->description;
        // dd(Auth::user()->id);
        $course->created_by_id = Auth::user()->id;
        $course->modified_by_id = Auth::user()->id;

        // kiểm tra xem người dùng có gửi ảnh hay ko nếu có thì thêm ảnh ngược lại ảnh sẽ là null 
        if (!$request->image) {
            $course->save();
            return redirect(route('courses.list'))->with('message', 'Thêm khóa học thành công');
        }
        $image = $request->image;
        // dd($image);
        $fileName = Str::random(40) . '.' . $image->getClientOriginalExtension();
        // dd($fileName);
        $path = $image->storeAs('images', $fileName, 'public');

        $course->image = $fileName;
        $course->save();

        return redirect(route('courses.list'))->with('message', 'Thêm khóa học thành công');
    }


    public function formEdit($id, Request $request)
    {
        // Lấy ra danh sách category để điền vào thẻ select
        $categorylist = Category::all();
        // dd($categorylist);

        // Lấy ra thông tin bản ghi course cần sửa
        $course = Course::with(['category'])->find($id);
        // dd($course->category->name);

        // Kiểm tra xem id có tồn tại hay ko phòng trường hợp người dùng đổi id trên url
        if (!$course) {
            return back();
        }

        return view('admin.web.courses.Edit', compact('categorylist', 'course'));
    }

    public function updateData($id, CourseRequest $request)
    {
        // Tìm kiếm bản ghi cần sửa
        $course = Course::find($id);
        $course->course_name = $request->name;
        $course->price = $request->price;
        $course->category_id = $request->category;
        $course->description = $request->description;
        $course->modified_by_id = Auth::user()->id;

        // Kiểm tra xem người dùng có gửi ảnh không
        $image = $request->image;
        // dd($image);
        if (!$image) {
            $course->save();
            return redirect(route('courses.list'))->with('message', 'Sửa khóa học thành công');
        }
        
        // Kiểm tra xem có ảnh cũ hay không nếu có thì xóa file cũ và thêm file mới còn không thì thêm luôn file mơis
        $image_old = $course->image;
        // dd($image_old);
        if (!empty($image_old)) {
            // Xóa file cũ
            File::delete(storage_path('app/public/images/' . $image_old));
        }

        // Đổi tên và lưu ảnh 
        $fileName = Str::random(40) . '.' . $image->getClientOriginalExtension();
        // dd($fileName);
        $path = $image->storeAs('images', $fileName, 'public');
        $course->image = $fileName;
        $course->save();

        return redirect(route('courses.list'))->with('message', 'Sửa khóa học thành công');

    }

    public function export(Request $request){
        // dd($request->sort);
        return Excel::download(new CoursesExport($request->search, $request->sort, $request->order, $request->case), 'courses.xlsx');
    }

    public function importForm(){
        return view('admin.web.courses.import');
    }

    public function import(ImportRequest $request) {
        $file = $request->excel;
        // dd($file);

        Excel::import(new ImportExcel, $file);

        return redirect(route(session('name')))->with('message', 'Import thành công.');
    }
}
