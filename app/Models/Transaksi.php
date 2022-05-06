<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon; //library time

class Transaksi extends Model
{
    use HasFactory;

    protected $primaryKey = 'idTransaksi';

    public $incrementing = false;

    protected $fillable = [
        'idTransaksi', 'idPegawai', 'idCustomer', 'idPembayaran', 'idDriver', 'tanggalTransaksi', 'tanggalWaktuSewa', 'tanggalWaktuSelesai',
        'tanggalWaktuKembali', 'statusTransaksi', 'rateDriver', 'performaDriver',
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

    public function Pegawai(){
        return $this->belongsTo(Pegawai::class, 'idPegawai', 'idPegawai'); //sebelah kanan milik tabel Pegawai, 
                                                                            //sebelah kiri miliki tabel Transaksi
    }

    public function Customer(){
        return $this->belongsTo(User::class, 'idCustomer', 'idCustomer'); //sebelah kanan milik tabel Customer, 
                                                                    //sebelah kiri miliki tabel Transaksi
    }

    public function Pembayaran(){
        return $this->belongsTo(Pembayaran::class, 'idPembayaran', 'idPembayaran'); //sebelah kanan milik tabel Pembayaran, 
                                                                                    //sebelah kiri miliki tabel Transaksi
    }

    public function Driver(){
        return $this->belongsTo(Driver::class, 'idDriver', 'idDriver'); //sebelah kanan milik tabel Driver, 
                                                                        //sebelah kiri miliki tabel Transaksi
    }
}