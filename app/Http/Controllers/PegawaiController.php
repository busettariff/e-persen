<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;

class PegawaiController extends Controller
{
    public function index(Request $request)
    {

        $query = Pegawai::query();
        $query->select('users.*','nama_mapel');
        $query->join('mapel','users.kode_mapel','=','mapel.kode_mapel');
        $query->orderBy('nama_lengkap');
        if(!empty($request->nama_pegawai)){
            $query->where('nama_lengkap','like','%'.$request->nama_pegawai.'%');
        }
        if(!empty($request->kode_mapel)){
            $query->where('users.kode_mapel', $request->kode_mapel);
        }

        $pegawai = $query->paginate(10);

        $mapel = DB::table('mapel')
        ->get();

        return view('pegawai.index', compact(
            'pegawai',
            'mapel'
        ));
    }

    public function store(Request $request)
    {
        $username = $request->username;
        $nama_lengkap = $request->nama_lengkap;
        $jabatan = $request->jabatan;
        $alamat = $request->alamat;
        $no_hp = $request->no_hp;
        $email = $request->email;
        $password = Hash::make($request ->password);
        $level = $request->level;
        $kode_mapel= $request->kode_mapel;
        if($request->hasFile('foto')){
            $foto = $username.".".$request->file('foto')->getClientOriginalExtension();
        } else {
            $foto = null;
        }

        try {
            $data = [
                'username' => $username,
                'nama_lengkap' => $nama_lengkap,
                'jabatan' => $jabatan,
                'alamat' => $alamat,
                'no_hp' => $no_hp,
                'email' => $email,
                'password' => $password,
                'level' => $level,
                'kode_mapel' => $kode_mapel,
                'foto' => $foto,
            ];
            $existingRecord = DB::table('users')->where('username', $username)->first();

            if ($existingRecord) {
            return Redirect::back()->with(['warning' => 'UNPTK sudah ada']);
            }

            $simpan = DB::table('users')->insert($data);
            if ($simpan) {
                if ($request->hasFile('foto')) {
                    $folderPath = "public/uploads/pegawai/";
                    $request->file('foto')->storeAs($folderPath, $foto);
                }
                return Redirect::back()->with(['success' => 'Data Berhasil Disimpan']);
            }
        } catch (\Throwable $e) {
            // dd($e);
            return Redirect::back()->with(['warning' => 'Data Gagal Disimpan']);
        }


    }

    public function edit(Request $request)
    {
        $username = $request->username;
        $mapel = DB::table('mapel')->get();
        $user = DB::table('users')->where('username', $username)->first();
        return view('pegawai.edit', compact(
            'mapel',
            'user'
        ));
    }

    public function update($username, Request $request)
    {
        $username = $request->username;
        $nama_lengkap = $request->nama_lengkap;
        $jabatan = $request->jabatan;
        $alamat = $request->alamat;
        $no_hp = $request->no_hp;
        $email = $request->email;
        $password = Hash::make($request ->password);
        $level = $request->level;
        $kode_mapel= $request->kode_mapel;
        $old_foto = $request->old_foto;
        if($request->hasFile('foto')){
            $foto = $username.".".$request->file('foto')->getClientOriginalExtension();
        } else {
            $foto = $old_foto;
        }

        try {
            if(empty($request->password)) {
                $data = [
                    'username' => $username,
                    'nama_lengkap' => $nama_lengkap,
                    'jabatan' => $jabatan,
                    'alamat' => $alamat,
                    'no_hp' => $no_hp,
                    'email' => $email,
                    'level' => $level,
                    'kode_mapel' => $kode_mapel,
                    'foto' => $foto,
                ];
            } else {
                $data = [
                    'username' => $username,
                    'nama_lengkap' => $nama_lengkap,
                    'jabatan' => $jabatan,
                    'alamat' => $alamat,
                    'no_hp' => $no_hp,
                    'email' => $email,
                    'password' => $password,
                    'level' => $level,
                    'kode_mapel' => $kode_mapel,
                    'foto' => $foto,
                ];
            }

            $update = DB::table('users')->where('username',$username)->update($data);
                if ($update) {
                    if ($request->hasFile('foto')) {
                        $folderPath = "public/uploads/pegawai/";
                        $folderPathOld = "public/uploads/pegawai/" .$old_foto;
                        Storage::delete($folderPathOld);
                        $request->file('foto')->storeAs($folderPath, $foto);
                    }
                return Redirect::back()->with(['success' => 'Data Berhasil Diupdate']);
                }
            } catch (\Exception $e) {
            // dd($e);
            return Redirect::back()->with(['warning' => 'Data Gagal Diupdate']);
        }

    }

    public function delete($username){
        $delete = DB::table('users')->where('username',$username)->delete();
        if($delete){
            return Redirect::back()->with(['success'=>'Data Berhasil dihapus']);
        }else{
            return Redirect::back()->with(['success'=>'Data Gagal dihapus']);
        }

    }
}
