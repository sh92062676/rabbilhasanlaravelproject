<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    function Login(){
        return view('login');
    }
    function Logout(Request $request){
        $request->session()->flush();
        return redirect('/admin');
    }

    function onLogin(Request $request){
        $user = $request->input('user');
        $pass = $request->input('pass');
        $verify = Admin::where('userName','=',$user)->where('password','=',$pass)->count();
        if($verify==1){
            $request->session()->put('user',$user);
            return 1;
        }
        else{
            return 0;
        }
    }
}
