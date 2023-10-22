<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserData extends Seeder
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
                'name' => 'Administrator',
                'username' => 'admin',
                'kelas' => 'ADMIN',
                'password' =>bcrypt('12345'),
                'level' => 1,
                'email' => 'admin@gmail.com'
            ],
            [
                'name' => 'student',
                'kelas' => 'XI RPL 2',
                'username' => 'student',
                'password' =>bcrypt('12345'),
                'level' => 2,
                'email' => 'arif@gmail.com'
            ],
      ];

      foreach($user as $key => $value){
        User::create($value);
      }

    }
}