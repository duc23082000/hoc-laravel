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
    public function createPost(AuthRequest $request)
    {
        // Băm mật khẩu và lưu thông tin user
        $password = Hash::make($request->password);
        $user = new UserModel();
        $user->email = $request->email;
        $user->password = $password;
        $user->save();

        return redirect(route('login'))->with('message2', 'Đăng ký thành công vui lòng đăng nhập tài khoản');
    }
}
