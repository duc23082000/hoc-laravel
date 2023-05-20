<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'courses';
    public $timestamps = true;
    protected $fillable = ['course_name', 'price', 'description', 'image', 'created_by_id', 'modified_by_id'];
    protected $dates = ['deleted_at'];

    public function categories() {
        return $this->hasMany(Category::class);
    }

    public function users() {
        return $this->hasMany(UserModel::class);
    }

    // public function users_update() {
    //     return $this->hasMany(UserModel::class);
    // }
}
