<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{   
    // public function index(){
    //     dd($username);
    //     return redirect(route('home'));
    // }
    public function home(){

        return view('admin.web.home');
    }
}
