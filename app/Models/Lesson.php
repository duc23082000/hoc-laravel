<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lesson extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'lessons';
    public $timestamps = true;
    protected $fillable = ['lesson_name', 'course_id', 'content', 'video', 'created_by_id', 'modified_by_id'];
    protected $dates = ['deleted_at'];

    public function course(){
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }
    public function user_create(){
        return $this->belongsTo(UserModel::class, 'created_by_id', 'id');
    }
    public function user_update(){
        return $this->belongsTo(UserModel::class, 'modified_by_id', 'id');
    }
}
