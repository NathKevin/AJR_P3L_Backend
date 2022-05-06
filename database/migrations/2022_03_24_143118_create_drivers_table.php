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
        Schema::create('drivers', function (Blueprint $table) {
            $table->string('idDriver')->primary()->default(' ');
            $table->string('namaDriver');
            $table->string('alamatDriver');
            $table->date('tanggalLahirDriver');
            $table->string('jenisKelaminDriver');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('noTelpDriver');
            $table->string('bahasa');
            $table->boolean('statusKetersediaanDriver');
            $table->double('hargaSewaDriver');
            $table->float('rerataRating');
            $table->string('fotoDriver');
            $table->string('fotocopySIM');
            $table->string('bebasNAPZA');
            $table->string('kesehatanJiwa');
            $table->string('kesehatanJasmani');
            $table->string('SKCK');
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
        Schema::dropIfExists('drivers');
    }
};