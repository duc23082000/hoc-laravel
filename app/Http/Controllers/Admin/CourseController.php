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
use App\Enums\CourseStatusEnum;

use App\Exports\CoursesExport;
use App\Http\Requests\ListRequest;
use App\Imports\ImportExcel;
use App\Jobs\ImportQueue;
use App\Models\ImportNotice;
use App\Models\Lesson;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class CourseController extends Controller
{

    public function list(ListRequest $request) {
        $status = CourseStatusEnum::getKeys();
        // dd($status[0]);

        // thông tin tìm kiếm
        $search = $request->search;

        // cột sắp xếp
        $collum = $request->sort;

        // phương thức săp xếp
        $order = $request->order;
        // dd($search);

        // Tạo order truyền vào dữ liệu để xuất ra excel
        $orderExport = $request->order;
        
        $case = $request->case;
        // dd($request->page);
        $page = $request->page;
        if(empty($request->all()) || $request->all() == ['page' => 1] ||  $request->all() == ['page' => null]){
            $joinResult = Cache::remember('joinResult', now()->addMinute(10), function (){
                return Course::with(['category', 'user_create', 'user_update'])
                ->paginate(20);     
            });
            return view('admin.web.courses.List', compact('search', 'joinResult', 'order', 'collum', 'case', 'orderExport', 'status'));
        }
        

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
            ->when(function ($query) use ($collum, $order, $case) {
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
        return view('admin.web.courses.List', compact('search', 'joinResult', 'order', 'collum', 'case', 'orderExport', 'status', 'page'));
    }

    public function show($id){
        // Lấy ra dữ liệu khóa học
        $data = Course::with(['user_update', 'user_create', 'category', 'lessons'])->find($id);
        // dd($data->lessons);
        // dd(CourseStatusEnum::getKey(1));

        if(!$data){
            return back();
        }

        return view('admin.web.courses.Show',compact('data'));
    }

    public function delete($id){
        $check = Lesson::where('course_id', $id)->first();
        // dd(!$check);
        if(!$check){
            // Chạy câu lệnh soft delete
            $delete = Course::destroy($id);
            // hoặc
            // $delete = Course::find($id)->delete();
            Cache::forget('joinResult');
    
            // dd($delete);
            return redirect(route('courses.list'))->with('message', 'Xóa thành công');
        }
        return redirect(route('courses.list'))->with('message2', 'Khóa học đã được đăng kí bài bạn phải giảng xóa bài giảng trước');
        
    }

    public function formAdd(){
        // Tạo Cache Lấy ra danh sách category để điền vào thẻ select
        $categorylist = Cache::remember('categorylist', now()->addMinute(10), function () {
            return Category::all();
        });
        // dd($categorylist[0]->id);
        

        $arrayCourseStatus = CourseStatusEnum::asArray();
        // dd($arrayCourseStatus);

        return view('admin.web.courses.Add', compact('categorylist', 'arrayCourseStatus'));
    }

    public function addData(CourseRequest $request)
    {
        
        // Tạo ra 1 khóa học mới 
        $course = new Course();
        $course->course_name = $request->name;
        $course->price = $request->price;
        $course->category_id = $request->category;
        $course->status = $request->status;
        $course->description = $request->description;
        // dd(Auth::user()->id);
        $course->created_by_id = Auth::user()->id;
        $course->modified_by_id = Auth::user()->id;
        Cache::forget('joinResult');
        

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
        // Tạo Cache Lấy ra danh sách category để điền vào thẻ select
        $categorylist = Cache::remember('categorylist', now()->addMinute(10), function () {
            return Category::all();
        });
        // dd($categorylist);

        // Lấy ra thông tin bản ghi course cần sửa
        $course = Course::with(['category'])->find($id);
        // dd($course->category->name);

        // lấy ra danh sách tên status
        $arrayCourseStatus = CourseStatusEnum::asArray();

        // Kiểm tra xem id có tồn tại hay ko phòng trường hợp người dùng đổi id trên url
        if (!$course) {
            return back();
        }

        return view('admin.web.courses.Edit', compact('categorylist', 'course', 'arrayCourseStatus'));
    }

    public function updateData($id, CourseRequest $request)
    {
        // Tìm kiếm bản ghi cần sửa
        $course = Course::find($id);
        $course->course_name = $request->name;
        $course->price = $request->price;
        $course->category_id = $request->category;
        $course->status = $request->status;
        $course->description = $request->description;
        $course->modified_by_id = Auth::user()->id;
        Cache::forget('joinResult');
        Cache::forget('dataShow'.$id);

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

    public function export(ListRequest $request){
        // dd($request->sort);
        
        return Excel::download(new CoursesExport($request->search, $request->sort, $request->order), 'courses.xlsx');
    }

    public function importForm(){
        $notication = ImportNotice::where('user_id', Auth::user()->id)
        ->orderBy('id', 'desc')
        ->get();
        return view('admin.web.courses.import', compact('notication'));
    }

    public function import(ImportRequest $request) {
        $files = $request->excel;

        foreach($files as $file){
            $file->storeAs('excels', $file->getClientOriginalName(), 'public');
        }
        ImportQueue::dispatch(Auth::user()->id, $request->url());
        
        Cache::forget('joinResult');

        return back()->with(['message'=>'Import thành công.']);
    }

    public function showError($id){
        $error = ImportNotice::find($id);
        // dd(!$error);
        if(!$error){
            return back();
        }
        // dd(is_string($error->notification));
        return view('admin.web.courses.ShowError', compact('error'));
    }

    public function deleteError(){
        ImportNotice::truncate();
        return back();
    }
}
