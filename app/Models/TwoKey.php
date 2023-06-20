<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TwoKey extends Model
{
    use HasFactory;
    protected $table = 'two_keys';
    protected $fillable = ['ip', 'user_id', 'otp', 'status'];
    public $timestamps = true;

}
