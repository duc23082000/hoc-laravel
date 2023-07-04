<?php

namespace App\Http\Requests;

use App\Enums\LessonStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LessonRequest extends FormRequest
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
            'course' => 'required|exists:courses,id,deleted_at,NULL', 
            'status' => ['required', Rule::in(LessonStatusEnum::asArray())] 
        ]; 
        // dd($rules);
        if ($this->file('video')) {
            // dd(1);
            $rules['video'] = 'mimes:mp4,avi,wmv';
        }
        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => 'Tên khóa học không được để trống',
            'name.string' => 'Tên khóa học phải là kiểu chuỗi',
            'name.max' => 'Tên khóa học chỉ được phép tối đa 255 kí tự',
            'course.required' => 'Vui lòng chọn dữ liệu',
            'course.exists' => 'Giá trị phải khớp với giá trị của bảng courses',
            'video.mimes' => 'Ảnh phải có định dạng mp4, avi, wmv',
            'video.max' => 'Ảnh phải có định dạng mp4, avi, wmv',
            'status.required' => 'Trạng thái không được để trống',
            'status.in' => 'Vui lòng Kiểm tra lại giá trị trạng thái',
        ];
    }
}
