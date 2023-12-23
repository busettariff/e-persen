<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = [
            [
                'nama_lengkap' => 'Arif Nugraha',
                'username' => '22113',
                'jabatan' => 'Administartor',
                'alamat' => 'Desa Sampora, Cilimus',
                'password' =>bcrypt('password'),
                'level' => 'admin',
                'email' => 'admin@gmail.com',
                'kode_mapel' => 'RPL'
            ],
            [
                'nama_lengkap' => 'Puput Septiana',
                'username' => '22112',
                'jabatan' => 'Guru',
                'alamat' => 'Desa Sampora',
                'no_hp' => +628497242,
                'password' =>bcrypt('password'),
                'level' => 'user',
                'email' => 'puput@gmail.com',
                'kode_mapel' => 'RPL'
            ],
            [
                'nama_lengkap' => 'Rizky you',
                'username' => '22111',
                'jabatan' => 'Guru',
                'alamat' => 'Desa Bandorasa',
                'no_hp' => +62864344242,
                'password' =>bcrypt('password'),
                'level' => 'user',
                'email' => 'arif@gmail.com',
                'kode_mapel' => 'RPL'
            ],
            [
                'nama_lengkap' => 'Fikry R',
                'username' => '22114',
                'jabatan' => 'Guru',
                'alamat' => 'Desa Sampora',
                'password' =>bcrypt('password'),
                'level' => 'user',
                'email' => 'fikry@gmail.com',
                'kode_mapel' => 'RPL'
            ],
      ];

      foreach($user as $key => $value){
        User::create($value);
      }

    }
}
