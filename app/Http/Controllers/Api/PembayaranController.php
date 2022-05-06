<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pembayaran;
use Validator; 

class PembayaranController extends Controller
{
    public function index(){
        $pembayaran = Pembayaran::all();
    
        if(count($pembayaran)>0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $pembayaran
            ], 200);
        }//return semua data
    
        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); //data empty
    }

    public function show(Request $request, $id){
        $pembayaran = Pembayaran::where('idPembayaran' , '=', $id)->first(); // mencari data berdasarkan id

        if(!is_null($pembayaran)){
            return response([
                'message' => 'Retrieve Pembayaran Success',
                'data' => $pembayaran
            ], 200);// Found
        }

        return response([
            'message' => 'Pembayaran Not Found',
            'data' => null
        ], 400);// not Found
    }

    public function create(Request $request){
        $createPembayaran = $request->all();
        $validate = Validator::make($createPembayaran, [
            'idMobil' => 'required',
            'idPromo' => 'required',
            'idDriver' => 'required',
            'metodePembayaran' => 'required|max:30',
            'totalPromo' => 'required|numeric',
            'totalBiayaMobil' => 'required|numeric',
            'totalBiayaDriver' => 'required|numeric',
            'dendaPeminjaman' => 'required|numeric',
            'totalBiaya' => 'required|numeric',
            'statusPembayaran' => 'required'
        ]);// validai inputan

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);// if validate errors
        
        $pembayaran = Pembayaran::create($createPembayaran);
        return response([
            'message' => 'Add Pembayaran Success',
            'data' => $pembayaran
        ], 200); // return data berupa json
    }

    public function update(Request $request, $id){
        $pembayaran = Pembayaran::where('idPembayaran' , '=', $id)->first(); // mencari data berdasarkan id

        if(is_null($pembayaran)){
            return response([
                'message' => 'Pembayaran not Found',
                'data' => null
            ], 400); // not Found
        }
        
        $updatePembayaran = $request->all();    
        $validate = Validator($updatePembayaran, [
            'idMobil' => 'required',
            'idPromo' => 'required',
            'idDriver' => 'required',
            'metodePembayaran' => 'required|max:30',
            'totalPromo' => 'required|numeric',
            'totalBiayaMobil' => 'required|numeric',
            'totalBiayaDriver' => 'required|numeric',
            'dendaPeminjaman' => 'required|numeric',
            'totalBiaya' => 'required|numeric',
            'statusPembayaran' => 'required'
        ]);// validai inputan 

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);// if validate errors
            
        //menimpa data
        $pembayaran->idMobil = $updatePembayaran['idMobil'];
        $pembayaran->idPromo = $updatePembayaran['idPromo'];
        $pembayaran->idDriver = $updatePembayaran['idDriver'];
        $pembayaran->metodePembayaran = $updatePembayaran['metodePembayaran'];
        $pembayaran->totalPromo = $updatePembayaran['totalPromo'];
        $pembayaran->totalBiayaMobil = $updatePembayaran['totalBiayaMobil'];
        $pembayaran->totalBiayaDriver = $updatePembayaran['totalBiayaDriver'];
        $pembayaran->dendaPeminjaman = $updatePembayaran['dendaPeminjaman'];
        $pembayaran->totalBiaya = $updatePembayaran['totalBiaya'];
        $pembayaran->statusPembayaran = $updatePembayaran['statusPembayaran'];

        if($pembayaran->save()){
            return response([
                'message' => 'Update Pembayaran Success',
                'data' => $pembayaran
            ], 200);
        }

        return response([
            'message' => 'Update Pembayaran Failed',
            'data' => null
        ], 400);
    }
}