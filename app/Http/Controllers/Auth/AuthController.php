<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Users;
use App\Models\UserModel;
use App\Http\Requests\AuthRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    private $user;
    
    public function __construct()
    {
        $this->user = new Users;
    }

    public function test(){
        // $allUser = collect(UserModel::insert(['email'=>'123@gmail.com', 'password'=>Hash::make('123456')]))->toArray();
        // dd($allUser);
        // $data = UserModel::where('id', 5)->get();
        // dd(collect($data)->toArray());
        // $data = UserModel::all();
        // foreach($data as $d){
        //     echo($d->email);
        // }
        // UserModel::chunk(200, function ($flights) {
        //     foreach ($flights as $flight) {
        //         echo $flight;
        //     }
        // });
        $data = UserModel::where('id', 5)->update(['token'=> csrf_token()]);
        dd($data);
        
    }

    public function signup() {
        return view('client.auth.signup');
    }

    public function createPost(AuthRequest $request){
        $password = Hash::make($request->password);
        $email = $request->email;
        if($request->password === $request->cfpassword) {
            UserModel::insert([
                'email' => $email,
                'password' => $password
            ]);
            return redirect(route('login'));
        } else {
                return back()->with(['message'=>'Mật khẩu không trùng khớp', 'email'=>$email]);
         }
    }

}
