<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function autenticate(Request $request) {
    $validator = Validator::make($request->all(), [
        'username' => 'required',
        'password' => 'required',
    ],
    [
        'username.required' => 'Username tidak boleh kosong !!',
        'password.required' => 'Password tidak boleh kosong !!',
    ]);

    if ($validator->fails()) {
        return back()->withErrors($validator)->withInput();
    }

    $kredensial = $request->only('username', 'password');

    if (Auth::attempt($kredensial)) {
        $request->session()->regenerate();
        $user = Auth::user();

        if ($user->level == 'admin') {
            return redirect()->intended('panel/home');
        } elseif ($user->level == 'user') {
            return redirect()->intended('dashboard');
        }

        return redirect()->intended('/');
    }

    return back()->with([
        'warning' => 'Maaf username atau password anda salah',
    ])->withInput();
}




    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    public function logoutadmin(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/panel');
    }
}
