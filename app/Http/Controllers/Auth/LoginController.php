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
use App\Jobs\QueueJob;
use App\Mail\EmailOtp;
use App\Mail\EmailVerified;
use App\Models\Category;
use App\Models\TwoKey;
use App\Models\User;
use Illuminate\Support\Str;
// use App\Http\Requests\AuthRequest;

// login logout 
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

// Login google
use Laravel\Socialite\Facades\Socialite;

// make password
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

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

        // Xác thực đăng nhập
        if (Auth::attempt($auth)) {

            // Kiểm tra xem người dùng có ghi nhớ tài khoản không
            if($request->remember){
                // dd($email);
                setcookie('email', $email, time()+(10*24*60*60));
                setcookie('password', $passwordInput, time()+(10*24*60*60));
            } else {
                setcookie('email', "");
                setcookie('password', "");
            }

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

    // form đổi mật khẩu
    public function changePasswordForm()
    {
        return view('admin.auth.changePassword');
    }

    // Xử lí đổi mật khẩu
    public function changePassword(ChangePassRequest $request)
    {
        $user = UserModel::find(Auth::user()->id);
        // Kiểm tra mật khẩu hiện tại
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng.']);
        }
        $email = Auth::user()->email;
        // Băm mật khẩu và update user
        $password = Hash::make($request->password);
        $user->password = $password;
        $user->save();


        // Đăng xuất yêu cầu đăng nhập lại
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect(route('login'))->with(['message2' => 'Đổi mật khẩu thành công vui lòng đăng nhập lại', 'email' => $email]);
    }

    // Đăng nhập bằng google
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // Xử lí đăng nhập bằng google
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

    // Gửi mail xác thực 
    public function sendVerificationEmail(){
        $token = Str::random(35);
        // dd($token);
        $user = UserModel::find(Auth::user()->id);
        
        $user->token = $token;
        $user->save();
        // Mail::to(Auth::user()->email)->send(new EmailVerified(Auth::user()->email, $token));
        QueueJob::dispatch(Auth::user()->email, $token);
        return back()->with(['message' => 'Gửi yêu cầu thành công! Vui lòng kiểm tra mail để Xác thực tài khoản']);
    }

    // Xác thực email
    public function verificationEmail($email, $token){
        $user = User::where('email', $email)->first();
        // dd($user['token']);
        if(!$user){
            return 'email khong ton tai';
        }

        if($token == $user['token']){
            $user->email_verified_at = now();
            $user->token = null;
            $user->save();
            return 'thanh cong';
        }
        return 'sai token';
    }
}
