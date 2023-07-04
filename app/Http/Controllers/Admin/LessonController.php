<?php

namespace App\Http\Controllers\Admin;

use App\Enums\LessonStatusEnum;
use App\Exports\LessonExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\ExcelRequest;
use App\Http\Requests\ImportRequest;
use App\Http\Requests\LessonRequest;
use App\Http\Requests\ListRequest;
use App\Jobs\HandleLesson;
use App\Jobs\ImportQueue;
use App\Models\Course;
use App\Models\ImportNotice;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;

class LessonController extends Controller
{
    public function list(ListRequest $request) {
        // thông tin tìm kiếm
        $search = $request->search;

        // cột sắp xếp
        $collum = $request->sort;

        // phương thức săp xếp
        $order = $request->order;
        // dd($search);

        // Tạo order truyền vào dữ liệu để xuất ra excel
        $orderExport = $request->order;
        
        $joinResult = Lesson::join('courses', 'lessons.course_id', '=', 'courses.id')
        ->join('users', 'lessons.created_by_id', '=', 'users.id')
        ->join('users as users2', 'lessons.modified_by_id', '=', 'users2.id')
        ->select('lessons.id', 'lessons.lesson_name', 'courses.course_name', 'lessons.status', 'users.email', 'users2.email as email2', 'lessons.created_at', 'lessons.updated_at')
        ->where('lessons.id', $search)
        ->orWhere('lessons.lesson_name', 'LIKE', "%$search%")
        ->orWhere('courses.course_name', 'LIKE', "%$search%")
        ->orWhere('users.email', 'LIKE', "%$search%")
        ->orWhere('users2.email', 'LIKE', "%$search%")
        ->orderBy($collum ?? 'lessons.updated_at', $order ?? 'desc')
        ->paginate(20);
        // dd($joinResult[0]);

        // Đổi phương thức sắp xếp liên tục sau mỗi lần click sắp xếp
        $order = $order == 'asc' ? 'desc' : 'asc';
        return view('admin.web.lessons.list', compact('joinResult', 'search', 'collum', 'order', 'orderExport'));
    }

    public function delete($id){
        $delete = Lesson::destroy($id);
        // dd($delete);
        return back()->with('message', 'Xóa thành công');
    }

    public function show($id){
        $data = Lesson::with(['course', 'user_create', 'user_update'])->find($id);
        return view('admin.web.lessons.show', compact('data'));
    }

    public function formAdd(Request $request){
        $courselist = Course::all();
        // dd($courselist);
        $enum = LessonStatusEnum::asArray();
        // dd($enum);
        
        return view('admin.web.lessons.add', compact('courselist', 'enum'));
    }

    public function addData(LessonRequest $request){
        $name = $request->name;
        $course_id = $request->course;
        // dd($request->descr);
        $content = $request->content;
        $user_id = Auth::user()->id;
        $video = $request->video;
        $status = $request->status;
        // dd($request->all());
        $url = $request->url();
        if($request->video){
            $fileName = Str::random(40) . '.' . ($video)->getClientOriginalExtension();
            $path = ($video)->storeAs('videos', $fileName, 'public');
        } else {
            $fileName = null;
        }
        HandleLesson::dispatch(null, $name, $course_id, $content, $user_id, $fileName, $url, $status);
        Cache::forget('dataShow'. $course_id);
        return redirect(route('lesson.list'))->with('message', 'Khóa học đang được tải lên vui lòng kiểm tra lại sau ít phút');
    }

    public function formEdit($id, Request $request){
        $courselist = Course::all();
        // dd($courselist);
        $lesson = Lesson::with('course')->find($id);

        $enum = LessonStatusEnum::asArray();

        // dd($lesson);
        return view('admin.web.lessons.edit', compact('courselist', 'lesson', 'enum'));
    }

    public function updateData($id, LessonRequest $request){

        $lesson = Lesson::find($id);

        $name = $request->name;
        $course_id = $request->course;
        $content = $request->content;
        $user_id = Auth::user()->id;
        $video = $request->video;
        $status = $request->status;
        $url = $request->url();
        if($request->video){
            File::delete(storage_path('app/public/videos/' . $lesson->video));
            $fileName = Str::random(40) . '.' . ($video)->getClientOriginalExtension();
            $path = ($video)->storeAs('videos', $fileName, 'public');
        } else {
            $fileName = null;
        }
        HandleLesson::dispatch($id, $name, $course_id, $content, $user_id, $fileName, $url, $status);
        return redirect(route('lesson.list'))->with('message', 'Khóa học đang được tải lên vui lòng kiểm tra lại sau ít phút');
    }
    
    public function export(ListRequest $request){
        return Excel::download(new LessonExport($request->search, $request->sort, $request->order), 'lessons.xlsx');
    }

    public function importForm(){
        $notication = ImportNotice::where('user_id', Auth::user()->id)
        ->orderBy('id', 'desc')
        ->paginate(5);
        // dd($notication[0]->status_name);
        return view('admin.web.lessons.import', compact('notication'));
    }

    public function import(ImportRequest $request){
        $files = $request->excel;
        // dd($files);
        // dd($request->url());
        foreach($files as $file){
            $file->storeAs('excels', $file->getClientOriginalName(), 'public');
        }
        ImportQueue::dispatch(Auth::user()->id, $request->url());
        // dd($a);
        Cache::forget('joinResult');

        return back()->with(['message'=>'Import thành công.']);
    }
}
