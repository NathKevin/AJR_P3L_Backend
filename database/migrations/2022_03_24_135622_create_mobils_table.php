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
        Schema::create('mobils', function (Blueprint $table) {
            $table->id('idMobil');

            $table->unsignedBigInteger('idMitra')->nullable();
            $table->foreign('idMitra')->references('idMitra')->on('mitras');
            
            $table->string('namaMobil');
            $table->string('tipeMobil');
            $table->string('jenisTransmisi');
            $table->string('jenisBahanBakar');
            $table->string('volumeBahanBakar');
            $table->string('warnaMobil');
            $table->string('kapasitasPenumpang');
            $table->string('fasilitas');
            $table->string('platNomor');
            $table->string('nomorSTNK');
            $table->string('kategoriAset');
            $table->double('hargaSewaMobil');
            $table->boolean('statusKetersediaanMobil');
            $table->date('tanggalTerakhirServis');
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
        Schema::dropIfExists('mobils');
    }
};