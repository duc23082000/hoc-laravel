<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Users;
use App\Models\UserModel;
use App\Http\Requests\AuthRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class CreateAccountController extends Controller
{


    // form đăng kí tài khoản
    public function signup() {
        return view('admin.auth.signup');
    }

    // xử lí đăng kí tài khoản 
    public function createPost(AuthRequest $request){

        $email = $request->email;

        // Kiểm tra xem mật khẩu có trùng khớp hay không
        if ($request->password === $request->cfpassword) {

            // Kiểm tra xem email này đã tạo tài khoản chưa
            $select = UserModel::where('email', $email)->get()->toArray();
            // dd($select)
            if (empty($select)) {
                // băm password và lưu dữ liệu
                $password = Hash::make($request->password);
                UserModel::insert([
                    'email' => $email,
                    'password' => $password
                ]);
                return redirect(route('login'));
            } else {
                return back()->with(['message2'=>'Email này đã được đăng kí. Vui lòng nhập email khác', 'email'=>$email]);
            }
            
        } else {
                return back()->with(['message'=>'Mật khẩu không trùng khớp', 'email'=>$email]);
         }
    }

}
