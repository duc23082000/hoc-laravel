<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CourseRequest;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

use App\Models\Category;
use App\Models\UserModel;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function list(Request $request) {
        $search = $request->search;
        $collum = $request->sort;
        $order = $request->order;

        // dd($search);
        $joinResult = DB::table('courses')
        ->join('categories', 'courses.category_id', '=', 'categories.id')
        ->select('courses.id', 'courses.course_name', 'courses.price', 'courses.created_at', 'courses.updated_at', 'categories.name')
        ->where('courses.id', $search)
        ->orwhere('courses.course_name', 'LIKE', '%'.$search.'%')
        ->orWhere('categories.name', 'LIKE', "%$search%")
        ->orderBy($collum ?? 'courses.updated_at', $order ?? 'desc')
        ->paginate(20);
        $dataJoin = collect($joinResult)->toArray();
        // dd($dataJoin);
        
        $order = $order == 'asc' ? 'desc' : 'asc';
        return view('client.web.courses.coursesList', compact('search', 'joinResult', 'dataJoin', 'order'));
    }

    public function show($id, $category){
        $course = Course::find($id);
        $image = $course->image;
        $name = $course->course_name;
        $price = $course->price;
        $created = UserModel::find($course->created_by_id)->email;
        $created_at = $course->created_at;
        $modified = UserModel::find($course->modified_by_id)->email;
        $updated_at = $course->updated_at;
        $description = $course->description;

        // dd($description);

        return view('client.web.courses.coursesShow',compact('category', 'image', 'name', 'price', 
                                                            'created', 'created_at', 'modified', 'updated_at',
                                                            'description'));
    }

    public function delete($id){
        $delete = Course::find($id);
        $delete->delete();
        // dd($delete);
        return redirect(route('courses.list'))->with('message', 'Xóa thành công');
    }

    public function formAdd(){
        $categorylist = Category::pluck('name')->toArray();
        // dd($categorylist);
        return view('client.web.courses.coursesAdd', compact('categorylist'));
    }

    public function addData(CourseRequest $request){
        // dd('haha');
        $select = Course::where('course_name', $request->name)->get()->toArray();
        // dd($select);
        if(empty($select)){
            // dd(1);
            $course = new Course();
            $course->course_name = $request->name;
            $course->price = $request->price;
            $course->category_id = Category::where('name', $request->category)->first()->id;
            $course->description = $request->description;
            // dd(Auth::user()->id);
            $course->created_by_id = Auth::user()->id;
            $course->modified_by_id = Auth::user()->id;
            $course->save();
            $id = Course::where('course_name', $request->name)->first()->id;

            if(!empty($request->image)) {
                $image = $request->image;
                $fileName = Str::random(40) . '.' . $image->getClientOriginalExtension();
                // dd($fileName);
                $path = $image->storeAs('images', $fileName, 'public');
                $courseUpdate = Course::find($id);
                $courseUpdate->image = $fileName;
                $courseUpdate->save();
                return redirect(route('courses.list'))->with('message', 'Thêm khóa học thành công');
            } else {
                return redirect(route('courses.list'))->with('message', 'Thêm khóa học thành công');
            }
            
        }
    }

    public function formEdit($id, Request $request){
        $categorylist = Category::pluck('name')->toArray();
        // dd($categorylist);
        $courseList = collect(Course::find($id))->toArray();
        // dd($courseList);
        $request->session()->put('id', $id);
        if(!empty($courseList)){
            $category = Category::find($courseList['category_id'])->name;
            // dd($category);
            return view('client.web.courses.coursesEdit', compact('categorylist', 'courseList', 'category'));
        } else {
            return back();
        }
    }

    public function updateData(CourseRequest $request){
        $id = session('id');
        // dd($id);
        $select =  collect(Course::where('course_name', $request->name)->where('id', '!=', $id)->get())->toArray();
        // dd($select);
        if(empty($select)) {
            $course = Course::find($id);
            $course->course_name = $request->name;
            $course->price = $request->price;
            $course->category_id = Category::where('name', $request->category)->first()->id;
            $course->description = $request->description;
            // dd(Auth::user()->id);
            $course->modified_by_id = Auth::user()->id;
            $course->save();

            if(!empty($request->image)) {
                $image_old = Course::find($id)->image;
                // dd($image_old);
                if(!empty($image_old)){
                    File::delete(storage_path('app/public/images/' . $image_old));
                    // dd(1);
                    $image = $request->image;
                    $fileName = Str::random(40) . '.' . $image->getClientOriginalExtension();
                    // dd($fileName);
                    $path = $image->storeAs('images', $fileName, 'public');
                    $courseUpdate = Course::find($id);
                    $courseUpdate->image = $fileName;
                    $courseUpdate->save();
                    return redirect(route('courses.list'))->with('message', 'Sửa khóa học thành công');
                } else {
                    $image = $request->image;
                    $fileName = Str::random(40) . '.' . $image->getClientOriginalExtension();
                    // dd($fileName);
                    $path = $image->storeAs('images', $fileName, 'public');
                    $courseUpdate = Course::find($id);
                    $courseUpdate->image = $fileName;
                    $courseUpdate->save();
                    return redirect(route('courses.list'))->with('message', 'Sửa khóa học thành công');
                }
            } else {
                return redirect(route('courses.list'))->with('message', 'Sửa khóa học thành công');
            }
        } else {
            return back()->with(['message2'=>'Tên Khóa học đã tồn tại',
            'name'=>$request->name, 'price'=>$request->price, 'category'=>$request->category,
            'description'=>$request->description]);
        }
        
    }
}
