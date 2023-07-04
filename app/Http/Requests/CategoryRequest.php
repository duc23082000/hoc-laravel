<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Category;

class CategoryRequest extends FormRequest
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
        // dd($this->input('id'));
        return [
            'name' => 'required|unique:categories,name,'. $this->input('id') .',id,deleted_at,NULL',
            'order' => 'required|integer|gt:0'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Tên sản phẩm không được để trống',
            'name.unique' => 'Tên sản phẩm đã tồn tại',
            'order.required' => 'Số lượng không được để trống',
            'order.integer' => 'Số lượng phải là số nguyên',
            'order.gt' => 'Số lượng phải lớn hơn 0'
        ];
    }
}
