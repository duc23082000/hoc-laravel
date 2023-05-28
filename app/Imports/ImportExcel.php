<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ImportExcel implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        $a = [];
        $data = [];
        foreach ($rows as $key => $row) {
            // dd($row);
            // dd($row['course_name']);
            $validator = Validator::make($row->toArray(), [
                'course_name' => 'required|string|max:255',
                'price' => 'required|integer|min:0',
                'category_id' => 'required|integer|exists:categories,id,deleted_at,NULL'
            ], [
                'course_name.required' => 'Tên Khóa học không được để trống (A'. $key+2 .')',
                'course_name.string' => 'Tên Khóa học phải là kiểu chuỗi (A'. $key+2 .'(' .$row['course_name'].'))',
                'course_name.max' => 'Tên Khóa học chỉ được phép tối đa 255 kí tự (A'. $key+2 .'(' .$row['course_name'].'))',
                'price.required' => 'Giá không được để trống (B'. $key+2 .')',
                'price.integer' => 'Giá phải là số nguyên (B'. $key+2 .'(' .$row['price'].'))',
                'price.min' => 'Giá phải lớn hơn hoặc bằng 0 (B'. $key+2 .'(' .$row['price'].'))',
                'category_id.required' => 'Category id không được để trống (C'. $key+2 .')',
                'category_id.integer' => 'Category id phải là số nguyên (C'. $key+2 .'(' .$row['category_id'].'))',
                'category_id.exists' => 'Category id phải được đăng kí trong bảng categories (C'. $key+2 .'(' .$row['category_id'].'))',
            ]);
            // dd($validator);
            if($validator->fails()){
                // dd($validator->errors()->messages());
                $error = $validator->messages()->all();
                $a[] = $error;
            }
            $data[] = [
                'course_name' => $row['course_name'],
                'price' => $row['price'],
                'description' => $row['description'],
                'category_id' => $row['category_id'],
                'created_by_id' => Auth::user()->id,
                'modified_by_id' => Auth::user()->id,
                'created_at' =>now(),
                'updated_at' =>now()
            ];
        }
        if (!empty($a)) {          
            return back()->with('datas', $a)->with('name', 'import.form');
        } 

        Course::insert($data);
        return back()->with('name', 'courses.list');
    }
}
