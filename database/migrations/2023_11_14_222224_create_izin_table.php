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
        Schema::create('izin', function (Blueprint $table) {
            $table->id();
            $table->string('username',50);
            $table->date('tgl_izin');
            $table->string('status', 20)->comment('izin = izin, sakit = sakit');
            $table->string('keterangan', 255);
            $table->string('status_approved', 20)->comment('0 = pending, 1 = di setujui, 2 = di tolak')->default(0);
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
        Schema::dropIfExists('izin');
    }
};
