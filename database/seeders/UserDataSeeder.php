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
                'nama_lengkap' => 'Administrator',
                'username' => 'admin',
                'jabatan' => 'ADMIN',
                'password' =>bcrypt('password'),
                'level' => 1,
                'email' => 'admin@gmail.com'
            ],
            [
                'nama_lengkap' => 'Puput Septiana',
                'username' => 'puput',
                'jabatan' => 'guru Mtk',
                'password' =>bcrypt('password'),
                'level' => 2,
                'email' => 'puput@gmail.com'
            ],
            [
                'nama_lengkap' => 'Arif Nugraha',
                'username' => 'student',
                'jabatan' => 'student',
                'password' =>bcrypt('password'),
                'level' => 2,
                'email' => 'arif@gmail.com'
            ],
            [
                'nama_lengkap' => 'Fikry R',
                'username' => 'fikry',
                'jabatan' => 'Guru Rpl',
                'password' =>bcrypt('password'),
                'level' => 2,
                'email' => 'fikry@gmail.com'
            ],
      ];

      foreach($user as $key => $value){
        User::create($value);
      }

    }
}
