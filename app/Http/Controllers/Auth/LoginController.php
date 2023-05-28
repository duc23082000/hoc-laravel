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

// Login google
use Laravel\Socialite\Facades\Socialite;

// make password
use Illuminate\Support\Facades\Hash;


class LoginController extends Controller
{
    // form đăng nhập 
    public function login()
    {
        return view('admin.auth.login');
    }

    // xử lí đăng nhập 
    public function checkLogin(Request $request)
    {
        // lấy mật khẩu nhập vào 
        $passwordInput = $request->password;
        $email = $request->email;


        // kiểm tra mật khẩu
        $auth = [
            'email' => $email,
            'password' => $passwordInput
        ];
        // dd(Auth::attempt($auth));
        if (Auth::attempt($auth)) {

            return redirect(route('home'));
        } else {
            return back()->with('message', 'Sai tên tài khoản hoặc mật khẩu')->with('email', $email);
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

    public function changePasswordForm()
    {
        return view('admin.auth.changePassword');
    }

    public function changePassword(ChangePassRequest $request)
    {

        // Băm mật khẩu và update user
        $password = Hash::make($request->password);
        $user = UserModel::find(Auth::user()->id);
        $user->password = $password;
        $user->save();

        // Đăng xuất yêu cầu đăng nhập lại 
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect(route('login'))->with(['message2' => 'Đổi mật khẩu thành công vui lòng đăng nhập lại', 'email' => $email]);
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $user = Socialite::driver('google')->user();
        $email = $user->email;
        // dd($email);

        $select = UserModel::where('email', $email)->first();
        // dd($select->id);

        if(empty($select)){
            $newUser = new UserModel();
            $newUser->email = $email;
            $newUser->password = '';
            $newUser->save();
            // dd(1);
            Auth::loginUsingId($newUser->id);
            return redirect(route('home'));
        }

        Auth::loginUsingId($select->id);
        return redirect(route('home'));
    }
}
