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
        $lok_kantor = DB::table('konfigurasi_lokasi')->where('id',1)->first();
        return view('presensi.create', compact('cek', 'lok_kantor'));
    }

    public function location()
    {
        return view('presensi.location');
    }

    public function store(Request $request)
    {
        $username = Auth::user()->username;
        $tgl_presensi = date("Y-m-d");
        $jam = date("H:i:s");
        $lok_kantor = DB::table('konfigurasi_lokasi')->where('id',1)->first();
        $lok = explode(",", $lok_kantor->lokasi_kantor);
        $latitudekantor = $lok[0];
        $longtitudekantor = $lok[1];
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


        if($radius > $lok_kantor->radius){
            echo "error|Maaf Anda Berada Diluar Radius, Jarak Anda".$radius."meter dari Kantor";
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
                echo "success|Terimakasih, Selamat Bekerja|in";
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
            $alamat = $request->alamat;
            $no_hp = $request->no_hp;
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
                    'alamat' => $alamat,
                    'no_hp' => $no_hp,
                    'email' => $email,
                    'foto' => $foto
                ];
            } else {
                $data = [
                    'nama_lengkap' => $nama_lengkap,
                    'alamat' => $alamat,
                    'no_hp' => $no_hp,
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

        public function histori()
        {
            $namabulan = ["","Januari","Febuari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
            return view('presensi.histori', compact(
                'namabulan'
            ));
        }

        public function gethistori(Request $request)
        {
            $bulan = $request->bulan;
            $tahun = $request->tahun;
            $username = Auth::user()->username;

            $histori = DB::table('presensi')
                ->whereRaw('MONTH(tgl_presensi)="' . $bulan . '"')
                ->whereRaw('YEAR(tgl_presensi)="' . $tahun . '"')
                ->where('username', $username)
                ->orderBy('tgl_presensi')
                ->get();

            return view('presensi.gethistori', compact(
                'histori'
            ));
        }

        public function izin()
        {
            $username = Auth::user()->username;
            $dataizin = DB::table('izin')->where('username',$username)->get();
            return view('presensi.izin', compact(
                'dataizin'
            ));
        }

        public function formizin()
        {
            return view('presensi.formizin');
        }

        public function storeizin(Request $request)
        {
            $username = Auth::user()->username;
            $tgl_izin = $request->tgl_izin;
            $status = $request->status;
            $keterangan = $request->keterangan;

            $data = [
                'username' => $username,
                'tgl_izin' => $tgl_izin,
                'status' => $status,
                'keterangan' => $keterangan
            ];

            $simpan = DB::table('izin')->insert($data);

            if($simpan){
                return redirect('/presensi/izin')->with(['success'=>'Data Berhasil Disimpan']);
            } else {
                return redirect('/presensi/izin')->with(['error'=>'Data Gagal Disimpan']);
            }
        }

        public function monitoring()
        {
            return view('presensi.monitoring');
        }

        public function getpresensi(Request $request)
        {
            $tanggal = $request->tanggal;
            $presensi = DB::table('presensi')
                ->select('presensi.*','nama_lengkap','nama_mapel','users.jabatan')
                ->join('users','presensi.username','=','users.username')
                ->join('mapel','users.kode_mapel','=','mapel.kode_mapel')
                ->where('tgl_presensi',$tanggal)
                ->get();

            return view('presensi.getpresensi',compact(
                'presensi'
             ));

        }

        public function showmaps(Request $request)
        {
            $id = $request->id;
            $presensi = DB::table('presensi')
            ->where('presensi.id', $id)
            ->join('users','presensi.username','=','users.username')
            ->first();
            return view('presensi.showmap', compact(
                'presensi'
            ));
        }

        public function laporan()
        {
            $namabulan = ["","Januari","Febuari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
            $user = DB::table('users')->orderBy('nama_lengkap')->get();
            return view('presensi.laporan', compact(
                'namabulan',
                'user'
            ));
        }

        public function cetaklaporan(Request $request)
        {
            $username = $request->username;
            $bulan = $request->bulan;
            $tahun = $request->tahun;
            $namabulan =
            ["","Januari","Febuari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
            $user = DB::table('users')->where('username', $username)
            ->join('mapel','users.kode_mapel','=','mapel.kode_mapel')
            ->first();

            $presensi = DB::table('presensi')
            ->where('username', $username)
            ->whereRaw('MONTH(tgl_presensi)="'.$bulan.'"')
            ->whereRaw('YEAR(tgl_presensi)="'.$tahun.'"')
            ->orderBy('tgl_presensi')
            ->get();
            return view('presensi.cetaklaporan', compact(
                'bulan',
                'tahun',
                'namabulan',
                'user',
                'presensi'
            ));

        }
}
