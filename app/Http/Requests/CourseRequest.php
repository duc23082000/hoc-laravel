<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
        return [
            'name' => 'required',
            'price' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/|gte:0',
            'category' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Tên khóa học không được để trống',
            'price.required' => 'Giá khóa học không được để trống',
            'price.numeric' => 'Giá khóa học phải là số thực',
            'price.regex' => 'Giá khóa chỉ được lấy tối đã 2 chữ số sau hàng thập phân',
            'price.gte' => 'Giá khóa học phải lớn hơn hoặc bằng 0',
            'category.required' => 'Vui lòng chọn dữ liệu'
        ];
    }

    
}
