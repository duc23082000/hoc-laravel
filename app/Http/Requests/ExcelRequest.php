<?php

namespace App\Http\Requests;
use App\Enums\CourseStatusEnum;
use Illuminate\Validation\Rule;

class ExcelRequest
{   

    private $categoryId;
    private $key;
    private $row;
    public function __construct($categoryId, $key, $row)
    {
        $this->categoryId = $categoryId;
        $this->key = $key;
        $this->row = $row;
        
    }

    public function rules()
    {
        $categoryId = $this->categoryId;
        $key = $this->key;
        $row = $this->row;
        
        // dd($statusArray);
        return [
            'course_name' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'category_id' => ['required', 'integer', Rule::in($categoryId)],
            'status' => ['required', Rule::in(CourseStatusEnum::asArray())]
        ];
    }

    public function messages()
    {
        $categoryId = $this->categoryId;
        $key = $this->key;
        $row = $this->row;
        return [
            'course_name.required' => 'Tên Khóa học không được để trống (A' . $key + 2 . ')',
            'course_name.string' => 'Tên Khóa học phải là kiểu chuỗi (A' . $key + 2 . '(' . $row['course_name'] . '))',
            'course_name.max' => 'Tên Khóa học chỉ được phép tối đa 255 kí tự (A' . $key + 2 . '(' . $row['course_name'] . '))',
            'price.required' => 'Giá không được để trống (B' . $key + 2 . ')',
            'price.integer' => 'Giá phải là số nguyên (B' . $key + 2 . '(' . $row['price'] . '))',
            'price.min' => 'Giá phải lớn hơn hoặc bằng 0 (B' . $key + 2 . '(' . $row['price'] . '))',
            'category_id.required' => 'Category id không được để trống (C' . $key + 2 . ')',
            'category_id.integer' => 'Category id phải là số nguyên (C' . $key + 2 . '(' . $row['category_id'] . '))',
            'category_id.in' => 'Category id phải được đăng kí trong bảng categories (C' . $key + 2 . '(' . $row['category_id'] . '))',
            'status.required' => 'Trạng thái Khóa học không được để trống (E' . $key + 2 . ')',
            'status.in' => 'Vui lòng kiểm tra giá trị status (E' . $key + 2 . '(' . $row['status'] . '))',
            
        ];
    }

}
