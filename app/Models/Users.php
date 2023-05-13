<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Users extends Model
{
    use HasFactory;

    public function createUser($data){
        $insert = DB::table('users')
        ->insert([
            'email' => $data['email'],
            'password' => $data['password']
        ]);
        return $insert;
    }

    public function loginUser($data) {
        $password = DB::table('users')
        ->select('password')
        ->where('email', $data)
        ->get();
        // dd($password);
        
        // $password = DB::select('select password from users where email = ?', [$data]);
        // dd($password[0]->password);
        return collect($password)->toArray();
    }

    public function checkEmail($data) {
        $check = DB::table('users')->where('email', $data)->get();
        // dd(collect($check)->toArray());

        // $check = DB::select('select * from users where email = ?', [$data]);
        // dd($check);
        return collect($check)->toArray();
    }
}
