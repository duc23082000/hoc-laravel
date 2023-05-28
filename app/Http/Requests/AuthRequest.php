<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthRequest extends FormRequest
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
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:6|same:cfpassword'
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Vui lòng điền :attribute',
            'string' => ':attribute không được chứa các kí tự đặc biệt',
            'email' => 'Nhập đúng định dạng email',
            'password' => ':attribute phải có ít nhất 6 kí tự',
            'unique' => ':attribute đã được đăng kí vui lòng sử dụng :attribute khác',
            'password.same' => 'Mật khẩu không trùng khớp'
        ];
    }

    public function attributes()
    {
        return[
            'email' => 'email',
            'password' => 'Mật khẩu'
        ];
    }
}   

