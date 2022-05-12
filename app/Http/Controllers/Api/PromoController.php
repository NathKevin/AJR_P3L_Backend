<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Promo;
use Validator; 
use Carbon\Carbon; //library time

class PromoController extends Controller
{
    public function index(){
        $today = Carbon::now()->format('l');
        $date = Carbon::now()->format('ymd');
        $promo = Promo::all();
        
        $err_message = array(array('Empty'));
        if(count($promo)>0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $promo,
                'day' => $today,
                'date' => $date
            ], 200);
        }//return semua data
   
        return response([
            'message' => $err_message,
            'data' => null
        ], 400); //data empty
    }

    public function show($id){
        $promo = Promo::where('idPromo' , '=', $id)->first(); // mencari data berdasarkan id
        if(!is_null($promo)){
            return response([
                'message' => 'Retrieve Promo Success',
                'data' => $promo
            ], 200);
        }//return 1 data promo yang ditemukan berdasarkan id

        $err_message = array(array('Promo Not Found'));
        return response([
            'message' => 'Promo Not Found',
            'data' => null
        ], 200);// data tidak ditemukan return null
    }

    public function create(Request $request){
        $createPromo = $request->all();
        $validate = Validator::make($createPromo, [
            'kode' => 'required',
            'jenisPromo' => 'required',
            'besarPromo' => 'required|numeric',
            'keterangan' => 'required'
        ]);//validasi inputan promo

        $err_message = array(array('Semua Field Harus Terisi'));
        if($createPromo['kode'] == 'null' || $createPromo['jenisPromo'] == 'null' || $createPromo['besarPromo'] == 'null'
            || $createPromo['keterangan'] == 'null'){
                return response(['message' => $err_message], 400);
            }
            
        if($validate->fails())
            return response(['message' => $validate->errors()], 400); //return eror invalid input
        

        $promo = Promo::create($createPromo);
        return response([
            'message' => 'Add Promo Success',
            'data' => $promo
        ], 200); // return data berupa json
    }

    public function update(Request $request, $id){
        $promo = Promo::where('idPromo' , '=', $id)->first(); // mencari data berdasarkan id

        $err_message = array(array('Promo Not Found'));
        if(is_null($promo)){
            return response([
                'message' => 'Promo Not Found',
                'data' => null 
            ]);
        }//data no found, return null

        $updatePromo = $request->all();
        $validate = Validator::make($updatePromo, [
            'kode' => 'required',
            'jenisPromo' => 'required',
            'besarPromo' => 'required|numeric',
            'keterangan' => 'required'
        ]);//validate inputan user

        $err_message = array(array('Semua Field Harus Terisi'));
        if($updatePromo['kode'] == 'null' || $updatePromo['jenisPromo'] == 'null' || $updatePromo['besarPromo'] == 'null'
            || $updatePromo['keterangan'] == 'null' || $updatePromo['kode'] == null || $updatePromo['jenisPromo'] == null || $updatePromo['besarPromo'] == null
            || $updatePromo['keterangan'] == null){
                return response(['message' => $err_message], 400);
            }

        if($validate->fails()){
            return response(['message' => $validate->errors()], 400); //return error invalid input
        }

        //menimpa data lama dengan data baru
        $promo->kode = $updatePromo['kode'];
        $promo->jenisPromo = $updatePromo['jenisPromo'];
        $promo->besarPromo = $updatePromo['besarPromo'];
        $promo->keterangan = $updatePromo['keterangan'];

        if($promo->save()){
            return response([
                'message' => 'Update Promo Success',
                'data' => $promo
            ], 200);
        }

        $err_message = array(array('Update Promo Failed'));
        return response([
            'message' => $err_message,
            'data' => null
        ], 400);
        
    }

    public function destroy($id){
        $promo = Promo::where('idPromo' , '=', $id)->first(); // mencari data berdasarkan id

        if(is_null($promo)){
            return response([
                'message' => 'Promo Not Found',
                'data' => null
            ], 404);
        }//return null, data tidak ditemukan

        if($promo->delete()){
            return response([
                'message' => 'Delete Promo Success',
                'data' => $promo
            ], 200);
        }//berhasil delete data

        return response([
            'message' => 'Delete Promo Failed',
            'data' => null
        ], 400);
    }//gagal menghapus data

}