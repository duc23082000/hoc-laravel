<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
// Models 
use App\Models\Users;
use App\Models\UserModel;

// Request
use Illuminate\Http\Request;
// use App\Http\Requests\AuthRequest;

// login logout 
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

// make password
use Illuminate\Support\Facades\Hash;


class LoginController extends Controller
{
    private $user;
    public function __construct()
    {
        $this->user = new Users;
    }
    public function login() {
        return view('client.auth.login');
    }

    public function checkLogin(Request $request){
        $passwordInput = $request->password;
        $email = $request->email;
        $password = collect(UserModel::select('password')->where('email', $email)->get())->toArray();
        // dd($password);
        $auth = [
            'email' => $email,
            'password' =>$passwordInput
        ];
        if(Auth::attempt($auth)){
            $request->session()->put('email', $email);
            // dd($email);
            $arr = explode("@", $email);
            // dd($arr[0]);
            return redirect(route('home'));
            // return  redirect()->route('home', ['username' => $arr]);
            // return view('home', [
            //     'username' => $request['email']
            // ]);
        } else {
            return redirect(route('login'))->with('message', 'Sai tên tài khoản hoặc mật khẩu')->with('email', $email);
        }
    }

    public function home(){
        // dd($username);
        $username = session('email');
        // dd($username);
        // $username = 'duc';
        return view('home', ['username' => $username]);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
    
        $request->session()->invalidate();
    
        $request->session()->regenerateToken();
        
        return redirect(route('login'));
    }

    public function changePasswordForm() {
        return view('client.auth.changePassword');
    }

    public function changePassword(Request $request){
        $request->validate([
            'password' => 'required|string|min:6',
        ], [
            'password.required' => 'Vui lòng điền Mật khẩu',
            'password.string' => 'Mật khẩu không được chứa các kí tự đặc biệt',
            'password.min' => 'Mật khẩu phải có ít nhất 6 kí tự',

        ]);

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
