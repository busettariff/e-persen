<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MapelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $mapels = [
            ['kode_mapel' => 'TKR', 'nama_mapel' => 'Teknik Kendaraan Ringan'],
            ['kode_mapel' => 'TSM', 'nama_mapel' => 'Teknik Sepede Motor'],
            ['kode_mapel' => 'RPL', 'nama_mapel' => 'Rekayasa Perangkat Lunak'],
            ['kode_mapel' => 'TKJ', 'nama_mapel' => 'Teknik Komputer Jaringan'],
            ['kode_mapel' => 'B.INDO', 'nama_mapel' => 'Bahasa Indonesia'],
            // Tambahkan data mapel lainnya sesuai kebutuhan
        ];

        // Insert data ke tabel 'mapel'
        DB::table('mapel')->insert($mapels);
    }
}
