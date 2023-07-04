<?php

namespace App\Http\Requests;

use App\Enums\LessonStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LessonExcelRequest extends FormRequest
{
    private $coursesId;
    private $key;
    private $row;

    public function __construct($coursesId, $key, $row)
    {
        $this->coursesId = $coursesId;
        $this->key = $key;
        $this->row = $row;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'lesson_name' => 'required|string|max:255',
            'course_id' => ['required', Rule::in($this->coursesId)],
            'status' => ['required', Rule::in(LessonStatusEnum::asArray())]
        ];
    }

    public function messages()
    {
        $row = $this->row;
        $key = $this->key;
        return [
            'lesson_name.required' => 'Tên không được bỏ trống(A' . $key+2 . ')',
            'lesson_name.string' => 'Tên Phải là kiểu chuỗi(A' . $key+2 . '(' . $row['lesson_name'] .'))',
            'lesson_name.max' => 'Tên chỉ được phép tối đa 255 kí tự(A' . $key+2 . '(' . $row['lesson_name'] .'))',
            'course_id.required' => 'Course_id không được bỏ trống(B' . $key+2 . ')',
            'course_id.in' => 'Course_id phải được đăng kí trong bảng courses(B' . $key+2 . '(' . $row['course_id'] .'))',
            'status.required' => 'Trạng thái không được bỏ trống(C' . $key+2 . ')',
            'status.in' => 'Kiểm tra lại giá trị trạng thái(C' . $key+2 . '(' . $row['status'] .'))',

        ];
    }
}
