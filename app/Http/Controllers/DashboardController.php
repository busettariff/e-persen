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
        $bulanini = date("m") * 1; //1 atau Januari
        $tahunini = date("Y"); //2023 atau tahun Sekarang
        $username = Auth::user()->username;
        $presensihariini = DB::table('presensi')->where('username',$username)->where('tgl_presensi',$hariini)->first();
        $historibulanini = DB::table('presensi')->whereRaw('MONTH(tgl_presensi)="'.$bulanini.'"')
            ->where('username', $username)
            ->whereRaw('MONTH(tgl_presensi)="'.$bulanini . '"')
            ->whereRaw('YEAR(tgl_presensi)="'.$tahunini . '"')
            ->orderBy('tgl_presensi')
            ->get();

        $rekappresensi = DB::table('presensi')
            ->selectRaw('COUNT(username) as jmlhadir, SUM(IF(jam_in > "07:05",1,0)) as jmlterlambat')
            ->where('username', $username)
            ->whereRaw('MONTH(tgl_presensi)="'.$bulanini . '"')
            ->whereRaw('YEAR(tgl_presensi)="'.$tahunini . '"')
            ->first();

        $leaderboard = DB::table('presensi')
            ->join('users', 'presensi.username', '=', 'users.username')
            ->where('tgl_presensi',$hariini)
            ->orderBy('jam_in')
            ->get();

        $namabulan = ["","Januari","Febuari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
        return view('dashboard.index',compact(
            'presensihariini',
            'historibulanini',
            'namabulan',
            'bulanini',
            'tahunini',
            'rekappresensi',
            'leaderboard'
        ));
    }
}
