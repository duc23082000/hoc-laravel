<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Course;
use GuzzleHttp\Psr7\Request;
use App\Enums\CourseStatusEnum;
use Illuminate\Validation\Rule;

class CourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {   
        $rules = [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/|gte:0',
            'category' => 'required|exists:categories,id,deleted_at,NULL',
            'status' => ['required', Rule::in(CourseStatusEnum::asArray())] 
        ]; 
        // dd($rules);
        if ($this->file('image')) {
            // dd(1);
            $rules['image'] = 'mimes:jpg,jpeg,png,gif|max:25000';
        }
        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => 'Tên khóa học không được để trống',
            'name.string' => 'Tên khóa học phải là kiểu chuỗi',
            'name.max' => 'Tên khóa học chỉ được phép tối đa 255 kí tự',
            'price.required' => 'Giá khóa học không được để trống',
            'price.numeric' => 'Giá khóa học phải là số thực',
            'price.regex' => 'Giá khóa chỉ được lấy tối đã 2 chữ số sau hàng thập phân',
            'price.gte' => 'Giá khóa học phải lớn hơn hoặc bằng 0',
            'category.required' => 'Vui lòng chọn dữ liệu',
            'category.exists' => 'Giá trị phải khớp với giá trị của bảng categories',
            'image.mimes' => 'Ảnh phải có định dạng jpg, png, gif và phải có kích thước nhỏ hơn 25MB',
            'image.max' => 'Ảnh phải có định dạng jpg, png, gif và phải có kích thước nhỏ hơn 25MB',
            'status.required' => 'Trạng thái không được để trống',
            'status.in' => 'Vui lòng Kiểm tra lại giá trị trạng thái',
        ];
    }

    
}
