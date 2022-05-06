<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon; //library time

class Driver extends Model
{
    use HasFactory;

    protected $primaryKey = 'idDriver';

    public $incrementing = false;

    protected $fillable =[
        'idDriver', 'namaDriver', 'alamatDriver', 'tanggalLahirDriver', 'jenisKelaminDriver', 'email', 'password', 'noTelpDriver',
        'bahasa', 'statusKetersediaanDriver', 'hargaSewaDriver', 'rerataRating', 'fotoDriver', 'fotocopySIM', 'bebasNAPZA',
        'kesehatanJiwa', 'kesehatanJasmani', 'SKCK', 'isActive','api_token', 'statusBerkas'
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
}