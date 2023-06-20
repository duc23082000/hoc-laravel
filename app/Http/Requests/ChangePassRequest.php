<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangePassRequest extends FormRequest
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
            'password' => 'required|string|min:6|same:cfpassword'
        ];
    }

    public function messages()
    {
        return [
            'password.required' => 'Vui lòng điền Mật khẩu',
            'password.string' => 'Mật khẩu không được chứa các kí tự đặc biệt',
            'password.min' => 'Mật khẩu phải có ít nhất 6 kí tự',
            'password.same' => 'Mật khẩu không trùng khớp vui lòng nhập lại',
        ];
    }
}
