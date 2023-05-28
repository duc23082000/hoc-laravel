<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class ImportRequest extends FormRequest
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
            'excel' => 'required|mimes:xlsx',
        ];
    }

    public function messages()
    {
        return [
            'excel.required' => 'Vui lòng gửi file',
            'excel.mimes' => 'Không đúng định dạng excel (.xlsx). Vui lòng kiểm tra lại'
        ];
    }

    
}
