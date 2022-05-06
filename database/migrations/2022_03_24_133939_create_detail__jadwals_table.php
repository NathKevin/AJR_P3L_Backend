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
        Schema::create('detail__jadwals', function (Blueprint $table) {
            $table->unsignedBigInteger('idJadwal');
            $table->foreign('idJadwal')->references('idJadwal')->on('jadwals');
            $table->unsignedBigInteger('idPegawai');
            $table->foreign('idPegawai')->references('idPegawai')->on('pegawais');
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
        Schema::dropIfExists('detail__jadwals');
    }
};