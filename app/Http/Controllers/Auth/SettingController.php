<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\TwoKey;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailOtp;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    public function index(){
        
        return view('admin.auth.setting');
    }

    public function onTwoKey(){
        // dd(Auth::user()->email_verified_at);
        if(Auth::user()->email_verified_at == null){
            return back();
        }
        $user = UserModel::find(Auth::user()->id);
        $status = $user->two_key === 0 ? 1 : 0;
        // dd($status);
        $user->two_key = $status;
        $user->save();
        // dd($user->two_key);
        if($user->two_key === 0){
            // Xoa toàn bộ ip của user này trong bảng two keys
            TwoKey::where('user_id', Auth::user()->id)->update(['status'=>0]);
            Cache::forget('check');
        }
        return back();
    }

    public function formOtp(Request $request){
        
        $twokey = TwoKey::where('ip', $request->ip())->where('user_id', Auth::user()->id)->first();

        if($twokey->status == 1){
            return redirect(route('home'));
        }
        return view('admin.auth.otp');
    }

    public function handleOtp(Request $request){
        $id = Session::get('idTwoKey');
        $twokey = TwoKey::find($id);
        if($request->otp == $twokey->otp){
            if ($request->updated_at > now()->addSeconds(-120)) {
                $otp = strval(random_int(100000, 999999));
                $twokey->otp = $otp;
                $twokey->save();
                Session::put('idTwoKey', $twokey->id);
                Mail::to(Auth::user()->email)->send(new EmailOtp($otp));
                return back()->with('message', 'otp hết hạn vui lòng kiểm tra mail và nhập lại otp');
            }
            $twokey->status = 1;
            $twokey->otp = null;
            $twokey->save();
            Cache::forget('check');
            return redirect(route('home'));
        }

        return back()->with('message', 'Sai Otp');
    }
}
