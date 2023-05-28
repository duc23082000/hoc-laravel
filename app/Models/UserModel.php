<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class UserModel extends Model
{
    use HasFactory, SoftDeletes ;
    protected $table = 'users';
    public $timestamps = true;
    protected $fillable = ['email', 'password'];
    protected $dates = ['deleted_at'];

    public function courses_created_by() {
        $this->hasMany(Course::class, 'created_by_id', 'id');
    }
    
}
