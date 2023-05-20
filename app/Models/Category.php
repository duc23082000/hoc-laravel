<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;
    public $timestamps = true;
    protected $fillable = ['name', 'oder'];
    protected $dates = ['deleted_at'];

    public function course(){
        return $this->belongsTo(Course::class);
    }
}
