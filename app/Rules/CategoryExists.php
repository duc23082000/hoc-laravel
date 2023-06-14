<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Category;

class CategoryExists implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    private $key;
    private $row;
    public function __construct($key, $row)
    {
        $this->key = $key;
        $this->row = $row;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return Category::where('id', $value)
            ->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Category id phải được đăng kí trong bảng categories (C'. $this->key+2 .'(' .$this->row['category_id'].'))';
    }
}
