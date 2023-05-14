<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{   
    // public function index(){
    //     dd($username);
    //     return redirect(route('home'));
    // }
    public function home(){
        // dd($username);
        // dd(request()->get('username'));
        // $username = 'duc';
        $username = explode("@", session('email'))[0];
        return view('client.web.home', ['username'=>$username]);
    }
}
