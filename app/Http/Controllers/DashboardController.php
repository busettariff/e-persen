<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $hariini = date("Y-m-d");
        $bulanini = date("m"); //1 atau Januari
        $tahunini = date("Y"); //2023 atau tahun Sekarang
        $username = Auth::user()->username;
        $presensihariini = DB::table('presensi')->where('username',$username)->where('tgl_presensi',$hariini)->first();
        $historibulanini = DB::table('presensi')->whereRaw('MONTH(tgl_presensi)="'.$bulanini.'"')
            ->whereRaw('YEAR(tgl_presensi)="'.$tahunini . '"')
            ->orderBy('tgl_presensi')
            ->get();
        return view('dashboard.index',compact('presensihariini', 'historibulanini'));
    }
}
