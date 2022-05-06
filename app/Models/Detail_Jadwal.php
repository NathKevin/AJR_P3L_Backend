<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon; //library time
use Illuminate\Database\Eloquent\Builder;

class Detail_Jadwal extends Model
{
    use HasFactory;

    protected $primaryKey = 'idDetailJadwal';

    protected $fillable = [
        'idJadwal', 'idPegawai'
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

        return $this->belongsTo(Pegawai::class, 'idPegawai', 'idPegawai'); //sebelah kanan milik tabel detail_jadwal, sebelah kiri miliki tabel pegawai

    }

    public function Jadwal(){

        return $this->belongsTo(Jadwal::class, 'idJadwal', 'idJadwal'); //sebelah kanan milik tabel detail_jadwal, sebelah kiri milik tabel jadwal

    }

    //     /**
    //  * Set the keys for a save update query.
    //  *
    //  * @param  \Illuminate\Database\Eloquent\Builder  $query
    //  * @return \Illuminate\Database\Eloquent\Builder
    //  */
    // protected function setKeysForSaveQuery(Builder $query)
    // {
    //     $keys = $this->getKeyName();
    //     if(!is_array($keys)){
    //         return parent::setKeysForSaveQuery($query);
    //     }

    //     foreach($keys as $keyName){
    //         $query->where($keyName, '=', $this->getKeyForSaveQuery($keyName));
    //     }

    //     return $query;
    // }

    // /**
    //  * Get the primary key value for a save query.
    //  *
    //  * @param mixed $keyName
    //  * @return mixed
    //  */
    // protected function getKeyForSaveQuery($keyName = null)
    // {
    //     if(is_null($keyName)){
    //         $keyName = $this->getKeyName();
    //     }

    //     if (isset($this->original[$keyName])) {
    //         return $this->original[$keyName];
    //     }

    //     return $this->getAttribute($keyName);
    // }
}