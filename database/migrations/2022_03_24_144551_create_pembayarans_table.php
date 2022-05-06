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
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id('idPembayaran');

            $table->unsignedBigInteger('idMobil');
            $table->foreign('idMobil')->references('idMobil')->on('mobils');
            $table->unsignedBigInteger('idPromo')->nullable();
            $table->foreign('idPromo')->references('idPromo')->on('promos');
            $table->string('idDriver')->nullable();
            $table->foreign('idDriver')->references('idDriver')->on('drivers');

            $table->string('metodePembayaran');
            $table->double('totalPromo');
            $table->double('totalBiayaMobil');
            $table->double('totalBiayaDriver');
            $table->double('dendaPeminjaman');
            $table->double('totalBiaya');
            $table->boolean('statusPembayaran');
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
        Schema::dropIfExists('pembayarans');
    }
};