<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotLoggedIn
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guest()) {
            return $next($request);
        } else {
            // Periksa jika pengguna yang diautentikasi memiliki peran 'admin'
            if (Auth::user()->level == 'admin') {
                // Arahkan ke panel admin atau rute admin tertentu
                return redirect()->back();
            }

            // Periksa jika pengguna yang diautentikasi memiliki peran 'user'
            if (Auth::user()->level == 'user') {
                // Arahkan ke dashboard pengguna atau rute pengguna tertentu
                return redirect()->back();
            }

            // Untuk kasus lain, Anda dapat mengarahkan ke rute default
            return redirect()->back();
        }
    }
}
