<?php

namespace App\Http\Controllers;

use App\Models\Presensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;

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

        // Edit Profile
        public function editprofile()
        {
            $username = Auth::user()->username;
            $user = DB::table('users')->where('username', $username)->first();
            return view('presensi.editprofile',
            compact(
                'user'
            ));
        }

        public function updateprofile(Request $request)
        {
            $username = Auth::user()->username;
            $nama_lengkap = $request->nama_lengkap;
            $email = $request->email;
            $password = Hash::make($request ->password);
            $user = DB::table('users')->where('username', $username)->first();
            if($request->hasFile('foto')){
                $foto = $username.".".$request->file('foto')->getClientOriginalExtension();
            } else {
                $foto = $user->foto;
            }
            if(empty($request->password)) {
                $data = [
                    'nama_lengkap' => $nama_lengkap,
                    'email' => $email,
                    'foto' => $foto
                ];
            } else {
                $data = [
                    'nama_lengkap' => $nama_lengkap,
                    'email' => $email,
                    'foto' => $foto,
                    'password' => $password
                ];
            }

            $update = DB::table('users')->where('username', $username)->update($data);

            if($update){
                if ($request->hasFile('foto')) {
                    $folderPath = "public/uploads/pegawai/";
                    $request->file('foto')->storeAs($folderPath, $foto);
                }
                return Redirect::back()->with(['success' => 'Data Berhasil di Update']);
            } else {
                return Redirect::back()->with(['error' => 'Data Gagal di Update']);
            }
        }
}
