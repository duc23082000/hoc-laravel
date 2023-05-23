<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'courses';
    public $timestamps = true;
    protected $fillable = ['course_name', 'price', 'description', 'image', 'created_by_id', 'modified_by_id'];
    protected $dates = ['deleted_at'];
    protected $qualifyColumnNames = true;

    public function category() {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function user_create() {
        return $this->belongsTo(UserModel::class, 'created_by_id', 'id');
    }

    public function user_update() {
        return $this->belongsTo(UserModel::class, 'modified_by_id', 'id');
    }
}
