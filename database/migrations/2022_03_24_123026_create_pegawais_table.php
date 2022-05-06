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
        Schema::create('pegawais', function (Blueprint $table) {
            $table->id('idPegawai');

            $table->unsignedBigInteger('idRole');
            $table->foreign('idRole')->references('idRole')->on('roles');
            
            $table->string('namaPegawai');
            $table->string('alamatPegawai');
            $table->date('tanggalLahirPegawai');
            $table->string('jenisKelaminPegawai');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('noTelpPegawai');
            $table->string('fotoPegawai')->nullable();
            $table->boolean('isActive');
            $table->string('api_token', 80)->uniqe()->nullable()->default(null);
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
        Schema::dropIfExists('pegawais');
    }
};