<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap');
            $table->string('username',50)->unique();
            $table->string('jabatan');
            $table->string('alamat')->nullable();
            $table->string('no_hp',13)->unique()->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('kode_mapel',10);
            $table->string('foto',30)->nullable();
            $table->enum('level',["admin","user"])->default("user");
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
