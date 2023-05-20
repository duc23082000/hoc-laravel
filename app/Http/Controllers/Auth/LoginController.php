<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
// Models 
use App\Models\Users;
use App\Models\UserModel;

// Request
use Illuminate\Http\Request;
use App\Http\Requests\ChangePassRequest;
// use App\Http\Requests\AuthRequest;

// login logout 
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

// make password
use Illuminate\Support\Facades\Hash;


class LoginController extends Controller
{
    // form đăng nhập 
    public function login() {
        return view('admin.auth.login');
    }

    // xử lí đăng nhập 
    public function checkLogin(Request $request){
        // lấy mật khẩu nhập vào 
        $passwordInput = $request->password;
        $email = $request->email;

        // lấy mật khẩu trên sever  
        $password = collect(UserModel::select('password')->where('email', $email)->get())->toArray();
        // dd($password);

        // kiểm tra mật khẩu
        $auth = [
            'email' => $email,
            'password' =>$passwordInput
        ];
        if(Auth::attempt($auth)){

            return redirect(route('home'));
        } else {
            return redirect(route('login'))->with('message', 'Sai tên tài khoản hoặc mật khẩu')->with('email', $email);
        }
    }
    
    // xử lí đăng xuất 
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
    
        $request->session()->invalidate();
    
        $request->session()->regenerateToken();
        
        return redirect(route('login'));
    }

    public function changePasswordForm() {
        return view('admin.auth.changePassword');
    }

    public function changePassword(ChangePassRequest $request){

        $email = session('email');
        // dd($email);
        if($request->password === $request->cfpassword){
            $password = Hash::make($request->password);
            // dd($email);
                
            $reset = UserModel::where('email', $email)->update(['password'=>$password]);
            Auth::logout();
            $request->session()->invalidate();
    
            $request->session()->regenerateToken();
            
            return redirect(route('login'))->with(['message2' => 'Đổi mật khẩu thành công vui lòng đăng nhập lại', 'email' => $email]);
            
        } else {
            return back()->with(['message'=>'Mật khẩu không trùng khớp']);
        }
    }
}
