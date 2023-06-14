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
    protected $fillable = ['email', 'password', 'token', 'email_verified_at', 'two_key'];
    protected $dates = ['deleted_at'];

    public function courses_created_by() {
        return $this->hasMany(Course::class, 'created_by_id', 'id');
        
    }

    public function courses_modified_by() {
        return $this->hasMany(Course::class, 'modified_by_id', 'id');
    }

    public function two_keys_user_id(){
        return $this->hasMany(TwoKey::class, 'user_id', 'id');
    }
    public function import_notice(){
        return $this->hasMany(ImportNotice::class, 'user_id', 'id');
    }
}
