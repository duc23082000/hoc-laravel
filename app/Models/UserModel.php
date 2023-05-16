<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class UserModel extends Model
{
    use HasFactory ;
    protected $table = 'users';
    public $timestamps = true;
    protected $fillable = ['email', 'password'];
}
