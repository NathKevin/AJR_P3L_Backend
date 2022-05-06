<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon; //library time

class Pembayaran extends Model
{
    use HasFactory;

    protected $primaryKey = 'idPembayaran';

    protected $fillable = [
        'idMobil', 'idPromo', 'idDriver', 'metodePembayaran', 'totalPromo', 'totalBiayaMobil', 'totalBiayaDriver', 
        'dendaPeminjaman', 'totalBiaya', 'statusPembayaran'
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

    public function Mobil(){
        return $this->belongsTo(Mobil::class, 'idMobil', 'idMobil'); //sebelah kanan milik tabel Mobil, 
                                                                    //sebelah kiri miliki tabel Pembayaran
    }

    public function Promo(){
        return $this->belongsTo(Promo::class, 'idPromo', 'idPromo'); //sebelah kanan milik tabel Promo, 
                                                                    //sebelah kiri miliki tabel Pembayaran
    }

    public function Driver(){
        return $this->belongsTo(Driver::class, 'idDriver', 'idDriver'); //sebelah kanan milik tabel Driver, 
                                                                    //sebelah kiri miliki tabel Pembayaran
    }
}