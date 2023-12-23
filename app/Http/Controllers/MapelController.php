<?php

namespace App\Http\Controllers;

use App\Models\mapel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class MapelController extends Controller
{
    public function index(Request $request)
    {
        $nama_mapel = $request->nama_mapel;
        $query = mapel::query();
        $query->select('*');
        if(!empty($nama_mapel)){
            $query->where('nama_mapel','like','%'.$nama_mapel.'%');
        }
        $mapel = $query->get();

        return view('mapel.index', compact(
            'mapel'
        ));
    }

    public function store(Request $request)
    {

        $kode_mapel = $request->kode_mapel;
        $nama_mapel = $request->nama_mapel;
        $data = [
            'kode_mapel' => $kode_mapel,
            'nama_mapel' => $nama_mapel,
        ];

        $existingRecord = DB::table('mapel')->where('kode_mapel', $kode_mapel)->first();

        if ($existingRecord) {
        return Redirect::back()->with(['warning' => 'Kode Mapel sudah ada']);
        }

        // Lanjutkan dengan penyisipan
        $simpan = DB::table('mapel')->insert($data);

        if ($simpan) {
        return Redirect::back()->with(['success' => 'Data Berhasil Disimpan']);
        } else {
        return Redirect::back()->with(['warning' => 'Data Gagal Disimpan']);
        }
    }

    public function edit(Request $request)
    {
        $kode_mapel = $request->kode_mapel;
        $mapel = DB::table('mapel')->where('kode_mapel', $kode_mapel)->first();
        return view('mapel.edit', compact(
            'mapel'
         ));
    }

    public function update($kode_mapel,Request $request)
    {
        $nama_mapel = $request->nama_mapel;
        $data = [
            'nama_mapel' => $nama_mapel
    ];
        $update = DB::table('mapel')->where('kode_mapel', $kode_mapel)->update($data);
        if($update){
            return Redirect::back()->with(['success'=>'Data Berhasil Diupdate']);
        }else{
            return Redirect::back()->with(['warning'=>'Data Gagal Diupdate']);
        }
    }

    public function delete($kode_mapel){
        $delete = DB::table('mapel')->where('kode_mapel',$kode_mapel)->delete();
        if($delete){
            return Redirect::back()->with(['success'=>'Data Berhasil dihapus']);
        }else{
            return Redirect::back()->with(['success'=>'Data Gagal dihapus']);
        }

    }
}