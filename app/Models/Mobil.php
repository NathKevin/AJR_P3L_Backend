<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon; //library time

class Mobil extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'idMobil';

    protected $fillable = [
        'idMitra', 'namaMobil', 'tipeMobil', 'jenisTransmisi', 'jenisBahanBakar', 'volumeBahanBakar', 'warnaMobil', 
        'kapasitasPenumpang', 'fasilitas', 'platNomor', 'nomorSTNK', 'kategoriAset', 'hargaSewaMobil', 'statusKetersediaanMobil', 'tanggalTerakhirServis',
        'gambarMobil', 'periodeKontrakMulai', 'periodeKontrakAkhir'
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

    public function Mitra(){

        return $this->belongsTo(Mitra::class, 'idMitra', 'idMitra'); //sebelah kanan milik tabel Mobil, sebelah kiri miliki tabel Mitra

    }
}