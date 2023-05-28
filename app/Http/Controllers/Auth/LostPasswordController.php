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
use App\Http\Requests\ChangePassRequest;
use Illuminate\Support\Carbon;
use App\Http\Requests\LossPassRequest;

class LostPasswordController extends Controller
{
    //  form Gửi email để nhận link đổi mk
    public function addEmail()
    {

        return view('admin.auth.lostPassword');
    }

    // Xử lí email đc gửi
    public function checkEmail(LossPassRequest $request)
    {
        $email = $request->email;
        // Tạo token và gửi link 
        // $token = Str::random(35);
        // // dd($token);
        // UserModel::where('email', $email)->update(['token'=> $token]);
        // Mail::to($email)->send(new Mailsend($email, $token));
        Password::sendResetLink(
            $request->only('email')
        );
        return back()->with(['message2' => 'Gửi yêu cầu thành công! Vui lòng kiểm tra mail để đổ mật khẩu', 'email' => $email]);
    }

    // form đổi mk 
    public function formReset(Request $request, $email, $token)
    {
        // check xem token link đã được tạo hay chưa
        $tokenCheck = collect(ResetpassModel::select('token')->where('email', $email)->get())->toArray();
        // dd($tokenCheck);
        // dd($token);
        if (!empty($tokenCheck)) {
            $request->session()->put('email', $email);
            $request->session()->put('token', $token);

            if (Hash::check($token, $tokenCheck[0]['token'])) {
                return view('admin.auth.resetPassword')->with(['email' => $email, 'token' => $token]);
            } else {
                return 'sai trang';
            }
        } else {
            return 'ko ton tai';
        }
    }

    public function resetPass(ChangePassRequest $request)
    {
        $email = session('email');
        $token = session('token');
        // dd($email);


        $timenow = Carbon::now()->addHours(-1)->format('Y-m-d H:i:s');
        // dd($timenow);
        $created_at = ResetpassModel::select('created_at')
            ->where('email', $email)
            ->get();

        // Kiểm tra xem link còn hạn hay không nếu còn thì đổi mật khẩu nếu hết hạn thì xóa link
        if ($timenow < $created_at[0]) {
            // băm password
            $password = Hash::make($request->password);
            // dd($email);

            // đổi password
            $reset = UserModel::where('email', $email)->update(['password' => $password]);

            // xóa link đổi mk  và quay lại trang đăng nhập
            $deleteToken = ResetpassModel::where('email', $email)->delete();
            // dd($deleteToken);
            return redirect(route('login'))->with(['message2' => 'Đổi mật khẩu thành công!', 'email' => $email]);
        } else {
            // xóa link đổi mật khẩu và quay lại trang gửi mail
            $deleteToken = ResetpassModel::where('email', $email)->delete();
            return redirect(route('lostPass'))->with(['message' => 'Liên kết đã hết hạn xin vui lòng nhập lại Email để nhận liên kết mới', 'email' => $email]);
        }
    }
}
