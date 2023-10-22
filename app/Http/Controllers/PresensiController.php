<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Presensi;

class PresensiController extends Controller
{
    public function create()
    {
        $hariini = date("Y-m-d");
        $username = Auth::user()->username;
        $cek = DB::table('presensi')->where('tgl_presensi', $hariini)->where('username', $username)->count();
        return view('presensi.create', compact('cek'));
    }

    public function store(Request $request)
    {
        $username = Auth::user()->username;
        $tgl_presensi = date("Y-m-d");
        $jam = date("H:i:s");
        $latitudekantor = -6.852764677224854;
        $longtitudekantor = 108.50440765696482;
        $lokasi = $request->lokasi;
        $lokasiuser = explode("," ,$lokasi);
        $latitudeuser = $lokasiuser[0];
        $longtitudeuser = $lokasiuser[1];

        $jarak = $this->distance($latitudekantor, $longtitudekantor, $latitudeuser, $longtitudeuser);
        $radius = round($jarak["meters"]);

        $cek = DB::table('presensi')->where('tgl_presensi', $tgl_presensi)->where('username', $username)->count();
        if($cek > 0){
            $ket = "out";
        }else {
            $ket = "in";
        }
        $image = $request->image;
        $folderPath = "public/uploads/absensi/";
        $formatName = $username ."-". $tgl_presensi."-". $ket ;
        $image_parts = explode(";base64", $image);
        $image_base64 = base64_decode($image_parts[1]);
        $fileName = $formatName . ".png";
        $file = $folderPath . $fileName;


        if($radius > 70){
            echo "error|Maaf Anda Berada Diluar Radius|";
        }else {
            if($cek > 0){
                $data_pulang = [
                'jam_out' => $jam,
                'foto_out' => $fileName,
                'lokasi_out' => $lokasi
                ];
                $update = DB::table('presensi')->where('tgl_presensi', $tgl_presensi)->where('username', $username)->update($data_pulang);
                if($update) {
                echo "success|Terimakasih, Hati-Hati Di Jalan|out";
                Storage::put($file, $image_base64);
                } else {
                echo "error|Maaf Gagal Absen, Hubungi Operator Sekolah|out";
                }
            } else {
                $data = [
                'username' => $username,
                'tgl_presensi' => $tgl_presensi,
                'jam_in' => $jam,
                'foto_in' => $fileName,
                'lokasi_in' => $lokasi
                ];
                $simpan = DB::table('presensi')->insert($data);
                if($simpan) {
                echo "success|Terimakasih, Selamat Belajar|in";
                Storage::put($file, $image_base64);
                } else {
                echo "error|Maaf Gagal Absen, Hubungi Operator Sekolah|in";
                }
            }
        }

    }
    //Menghitung Jarak
        function distance($lat1, $lon1, $lat2, $lon2)
        {
        $theta = $lon1 - $lon2;
        $miles = (sin(deg2rad($lat1)) * sin(deg2rad($lat2))) + (cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
        cos(deg2rad($theta)));
        $miles = acos($miles);
        $miles = rad2deg($miles);
        $miles = $miles * 60 * 1.1515;
        $feet = $miles * 5280;
        $yards = $feet / 3;
        $kilometers = $miles * 1.609344;
        $meters = $kilometers * 1000;
        return compact('meters');
        }
}
