<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon; //library time

class Pegawai extends Model
{
    use HasFactory;

    protected $primaryKey = 'idPegawai';

    protected $fillable = [
        'idRole', 'namaPegawai', 'alamatPegawai', 'tanggalLahirPegawai', 'jenisKelaminPegawai', 'email', 'password', 'noTelpPegawai', 'fotoPegawai', 'isActive', 'api_token'
    ];

    protected $hidden = [
        'password', 'api_token'
    ];

    public function getCreatedAtAttribute(){
        if(!is_null($this->attributes['created_at'])){
            return Carbon::parse($this->attributes['created_at'])->format('Y-m-d H:i:s');
        }
    } // convert format created_at menjadi Y-m-d H:i:s

    public function getUpdatedAtAttribute(){
        if(!is_null($this->attributes['updated_at'])){
            return Carbon::parse($this->attributes['updated_at'])->format('Y-m-d H:i:s');
        }
    } // convert format updated_at menjadi Y-m-d H:i:s

    public function Role(){

        return $this->belongsTo(Role::class, 'idRole', 'idRole'); //sebelah kanan milik tabel Role, sebelah kiri miliki tabel pegawai

    }
}