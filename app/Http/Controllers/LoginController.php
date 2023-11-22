<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index(){
        if($user = Auth::user()) {
            // if($user->level == '1'){
            //     return redirect()->intended('admin');
            // } elseif ($user->level == '2') {
            //     return redirect()->intended('student');
            // }
        }
        return view('login.login_view');
    }


    public function autenticate(Request $request){
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ],
        [
            'username.required' => 'Username tidak boleh kosong !!'
        ]
        );

        $kredensial = $request->only('username','password');

        if(Auth::attempt($kredensial)) {
            $request->session()->regenerate();
            $user = Auth::User();

             if($user->level == 'admin'){
                return redirect()->intended('admin');
            } elseif ($user->level == 'user') {
                return redirect()->intended('dashboard');
            }

            return redirect()->intended('/');
        }


        return back()->with([
            'warning' => 'Maaf username atau password anda salah',
        ])->onlyInput('warning');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
