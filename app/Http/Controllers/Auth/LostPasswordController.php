<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Users;
use App\Models\UserModel;
use Illuminate\Support\Facades\Mail;
use App\Mail\Mailsend;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;
use App\Models\ResetpassModel;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\AuthRequest;

class LostPasswordController extends Controller
{
    private $user;
    public function __construct()
    {
        $this->user = new Users;
    }

    public function addEmail(){
        return view('client.auth.lostPassword');
    }

    public function checkEmail(Request $request){
        $email = $request->email;
        $check = collect(UserModel::where('email', $email)->get())->toArray();
        // dd($check);
        

        if(!empty($check)){
            // $token = Str::random(35);
            // // dd($token);
            // UserModel::where('email', $email)->update(['token'=> $token]);
            // Mail::to($email)->send(new Mailsend($email, $token));
            Password::sendResetLink(
                $request->only('email')
            );
            return back()->with(['message2' => 'Gửi yêu cầu thành công! Vui lòng kiểm tra mail để đổ mật khẩu', 'email' => $email]);
        } else {
            // dd($email);
            return back()->with(['message' => 'Tài khoản không tồn tại', 'email' => $email]);
        }
    }

    public function formReset(Request $request, $email, $token) {
        $tokenCheck = collect(ResetpassModel::select('token')->where('email', $email)->get())->toArray();
        // dd($tokenCheck);
        // dd($token);
        if(!empty($tokenCheck)){
            $request->session()->put('email', $email);
            $request->session()->put('token', $token);

            if(Hash::check($token, $tokenCheck[0]['token'])){
                return view('client.auth.resetPassword')->with(['email'=>$email, 'token'=>$token]);
            } else {
                return 'sai trang';
            }
        } else {
            return 'ko ton tai';
        }
        
    }

    public function resetPass(Request $request){
        $request->validate([
            'password' => 'required|string|min:6',
        ], [
            'password.required' => 'Vui lòng điền Mật khẩu',
            'password.string' => 'Mật khẩu không được chứa các kí tự đặc biệt',
            'password.min' => 'Mật khẩu phải có ít nhất 6 kí tự',

        ]);
        // dd('hello');
        $email = session('email');
        $token = session('token');
        // dd($email);
        if($request->password === $request->cfpassword){
            $timenow = \Carbon\Carbon::now()->addHours(-1)->format('Y-m-d H:i:s');
            // dd($timenow);
            $created_at = ResetpassModel::select('created_at')
            ->where('email', $email)
            ->get()
            ->map(function ($item) {
                return $item->created_at->format('Y-m-d H:i:s');
            })
            ->toArray();
            
            // dd($created_at[0]);
            // $a = $timenow > $created_at[0] ? 'hello' : 'hi';
            // dd($a);
            if($timenow < $created_at[0]){
                $password = Hash::make($request->password);
                // dd($email);
                
                $reset = UserModel::where('email', $email)->update(['password'=>$password]);
                
                $deleteToken = ResetpassModel::where('email', $email)->delete();
                // dd($deleteToken);
                return redirect(route('login'))->with(['message2'=>'Đổi mật khẩu thành công!', 'email'=>$email]);
            } else {
                $deleteToken = ResetpassModel::where('email', $email)->delete();
                return redirect(route('lostPass'))->with(['message'=>'Liên kết đã hết hạn xin vui lòng nhập lại Email để nhận liên kết mới', 'email'=>$email]);
            }
            
        } else {
            return back()->with(['message'=>'Mật khẩu không trùng khớp']);
        }
    }
}
