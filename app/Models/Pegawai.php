<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;

class Pegawai extends Model
{
    use HasApiTokens, HasFactory, Notifiable, HasTimestamps;

    protected $table = "users";
    protected $primaryKey = "id";
    protected $fillable = [
        'username',
        'nama_lengkap',
        'email',
        'jabatan',
        'no_hp',
        'password',
        'foto',
        'kode_mapel',
        'level',
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];
}
