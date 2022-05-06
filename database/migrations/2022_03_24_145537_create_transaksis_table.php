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
        Schema::create('transaksis', function (Blueprint $table) {
            $table->string('idTransaksi')->primary();

            $table->unsignedBigInteger('idPegawai');
            $table->foreign('idPegawai')->references('idPegawai')->on('pegawais');
            $table->string('idCustomer');
            $table->foreign('idCustomer')->references('idCustomer')->on('users');
            $table->unsignedBigInteger('idPembayaran');
            $table->foreign('idPembayaran')->references('idPembayaran')->on('pembayarans');
            $table->string('idDriver')->nullable();
            $table->foreign('idDriver')->references('idDriver')->on('drivers');

            $table->datetime('tanggalTransaksi');
            $table->datetime('tanggalWaktuSewa');
            $table->datetime('tanggalWaktuSelesai');
            $table->datetime('tanggalWaktuKembali')->nullable();
            $table->string('statusTransaksi');
            $table->integer('rateDriver');
            $table->string('performaDriver')->nullable();
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
        Schema::dropIfExists('transaksis');
    }
};