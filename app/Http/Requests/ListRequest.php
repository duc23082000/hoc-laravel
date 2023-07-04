<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ListRequest extends FormRequest
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
        $rules = [];
        
        if($this->input('sort')){
            if($this->url() == 'http://127.0.0.1:8000/admin/courses/list'){
                $rules['sort'] = 'in:courses.updated_at,courses.created_at,email2,create_users.email,courses.status,categories.name,courses.price,courses.course_name,courses.id';
            }
            if($this->url() == 'http://127.0.0.1:8000/admin/categories/list'){
                $rules['sort'] = 'in:id,name,orders,created_at,updated_at';
            }
            if($this->url() == 'http://127.0.0.1:8000/admin/lesson/list'){
                $rules['sort'] = 'in:lessons.id,lessons.lesson_name,courses.course_name,lessons.created_at,lessons.updated_at,users.email,email2,lessons.status';
            }
        }
        if($this->input('order')){
            $rules['order'] = 'in:asc,desc';
        }
        if($this->input('search')){
            $rules['search'] = 'string';
        }
        return $rules;
        
    }
}

