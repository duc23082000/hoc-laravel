<?php

namespace App\Http\Middleware;

use App\Jobs\SendMailTwoKey;
use App\Mail\EmailOtp;
use App\Models\TwoKey;
use App\Models\UserModel;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class CheckTwoKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Kiểm tra xem người dùng có bật xt 2 yếu tố không
        if (Auth::user()->two_key === 1) {
            $ip = $request->ip();
            $check = Cache::remember('check', now()->addMinutes(60), function() use ($ip){
                return TwoKey::where('user_id', Auth::user()->id)->where('ip', $ip)->first();
            });
            // dd($check);
            $otp = strval(random_int(100000, 999999));
            
            // Mail::to(Auth::user()->email)->send(new EmailOtp($otp));

            if ($check == null) {
                // dd(1);
                $twokey = new TwoKey;
                $twokey->ip = $ip;
                $twokey->user_id = Auth::user()->id;
            } else {
                if ($check->status == 1) {
                    return $next($request);
                } 
                $twokey = TwoKey::find($check->id);
            }   
            $twokey->otp = $otp;
            SendMailTwoKey::dispatch(Auth::user()->email, $otp);
            $twokey->save();
            Session::put('idTwoKey', $twokey->id);
            return redirect(route('inputOtp'));
                 
        }
        return $next($request);
    }
}
