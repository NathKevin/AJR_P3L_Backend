<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mobil;
use Validator; 
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MobilController extends Controller
{
    public function index(){
        $start = Carbon::now()->format('ymd');
        $mobil = DB::table('mobils')
                                ->leftjoin('mitras' , 'mobils.idMitra' , '=', 'mitras.idMitra')
                                ->select(DB::raw("*, DATEDIFF(mobils.periodeKontrakAkhir, $start) as diff"))
                                ->where('mobils.idMobil' , '!=', null)
                                ->get();
    
        if(count($mobil)>0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $mobil
            ], 200);
        }//return semua data
        
        $err_message = array(array('Empty'));
        return response([
            'message' => $err_message,
            'data' => null
        ], 400); //data empty
    }

    public function getAvailableMobil(){
        $mobil = DB::table('mobils')
                ->select(DB::raw("*"))
                ->where('mobils.statusKetersediaanMobil' , '!=', 0)
                ->get();
    
        if(count($mobil)>0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $mobil
            ], 200);
        }//return semua data
        
        $err_message = array(array('Empty'));
        return response([
            'message' => $err_message,
            'data' => null
        ], 400); //data empty
    }

    public function showMobilOnContract(){
        $start = Carbon::now()->format('ymd');
        $mobil = Mobil::selectRaw("*, DATEDIFF(mobils.periodeKontrakAkhir, $start) as diff")
            ->join('mitras', 'mitras.idMitra', '=', 'mobils.idMitra')
            ->where('mitras.isActive' , '=', 1)
            ->get();

        // $mobil = DB::table('mobils')
        //                         ->join('mitras' , 'mobils.idMitra' , '=', 'mitras.idMitra')
        //                         ->select(DB::raw('*'))
        //                         ->where('mitras.isActive' , '=', 1)
        //                         ->orderBy('mitras.idMitra')
        //                         ->get()
        //                         ->map(function($item){
        //                             $start = Carbon::now()->format('ymd');
        //                             $end = Carbon::parse($item->periodeKontrakAkhir);
        //                             $item->diff = Carbon::parse($start)->diff($end, false) ;

        //                             return $item;
        //                         });

        if(count($mobil)>0){
            return response([
                'message' => 'Retrieve All Contract Success',
                'data' => $mobil
            ], 200);
        }//return semua data
        
        $err_message = array(array('Empty'));
        return response([
            'message' => $err_message,
            'data' => null
        ], 400); //data empty
    }

    public function show(Request $request, $id){
        $mobil = Mobil::where('idMobil' , '=', $id)->first(); // mencari data berdasarkan id

        if(!is_null($mobil)){
            return response([
                'message' => 'Retrieve Mobil Success',
                'data' => $mobil
            ], 200);//mobil Found
        }

        $err_message = array(array('Mobil Not Found'));
        return response([
            'message' => $err_message,
            'data' => null
        ], 400);//mobil not Found
    }

    public function create(Request $request){
        $createMobil = $request->all();
        $validate = Validator::make($createMobil, [
            'namaMobil' => 'required|max:60',
            'tipeMobil' => 'required|max:20',
            'jenisTransmisi' => 'required|max:20',
            'jenisBahanBakar' => 'required|max:20',
            'volumeBahanBakar' => 'required|numeric',
            'warnaMobil' => 'required|max:20',
            'kapasitasPenumpang' => 'required|max:20|numeric',
            'fasilitas' => 'required|max:60',
            'platNomor' => 'required',
            'nomorSTNK' => 'required|numeric',
            'kategoriAset' => 'required|max:20',
            'hargaSewaMobil' => 'required|numeric',
            'statusKetersediaanMobil' => 'required',
            'tanggalTerakhirServis' => 'required|date',
            'gambarMobil' => 'required|max:1024|mimes:jpg,png,jpeg|image',
            'periodeKontrakMulai' => 'required',
            'periodeKontrakAkhir' => 'after_or_equal:periodeKontrakMulai'
        ], [], 
        [
            'namaMobil' => 'Nama Mobil',
            'tipeMobil' => 'Tipe Mobil',
            'jenisTransmisi' => 'Jenis Transmisi',
            'jenisBahanBakar' => 'Jenis Bahan Bakar',
            'volumeBahanBakar' => 'Volume Bahan Bakar',
            'warnaMobil' => 'Warna Mobil',
            'kapasitasPenumpang' => 'Kapasitas Penumpang',
            'fasilitas' => 'Fasilitas',
            'nomorSTNK' => 'Nomor STNK',
            'kategoriAset' => 'Kategori Aset',
            'hargaSewaMobil' => 'Harga Sewa Mobil',
            'tanggalTerakhirServis' => 'Tanggal Terakhir Servis',
            'gambarMobil' => 'Gambar Mobil',
            'periodeKontrakMulai' => 'Tanggal Mulai Kontrak',
            'periodeKontrakAkhir' => 'Tanggal Selesai Kontrak'
        ]);// validai inputan
        
        $err_message = array(array('Pastikan Semua Field Terisi'));
        if($createMobil['namaMobil'] == 'null' || $createMobil['tipeMobil'] == 'null' || $createMobil['jenisTransmisi'] == 'null' || 
            $createMobil['jenisBahanBakar'] == 'null' || $createMobil['volumeBahanBakar'] == 'null' || $createMobil['warnaMobil'] == 'null' || 
            $createMobil['kapasitasPenumpang'] == 'null' || $createMobil['fasilitas'] == 'null' || $createMobil['platNomor'] == 'null' || 
            $createMobil['nomorSTNK'] == 'null' || $createMobil['kategoriAset'] == 'null' || $createMobil['hargaSewaMobil'] == 'null' ||
            $createMobil['tanggalTerakhirServis'] == 'null'){
                return response(['message' => $err_message], 400); //return eror invalid input
        }

        if($createMobil['kategoriAset'] == 'Sewa'){
            if($createMobil['periodeKontrakMulai'] == 'null'){
                $err_message = array(array('Tanggal Mulai Kontrak Harus Terisi'));
                return response(['message' => $err_message], 400); //return eror invalid input
            }
            if($createMobil['periodeKontrakAkhir'] == 'null'){
                $err_message = array(array('Tanggal Selesai Kontrak Harus Terisi'));
                return response(['message' => $err_message], 400); //return eror invalid input
            }
            if($createMobil['idMitra'] == 'null'){
                $err_message = array(array('Nama Mitra Harus Terisi'));
                return response(['message' => $err_message], 400); //return eror invalid input
            }
        }
        
        if($validate->fails())
            return response(['message' => $validate->errors()], 400);// if validate errors
        
        if($createMobil['idMitra'] == 'null'){
            $createMobil['idMitra'] = NULL;
        }
        if($createMobil['periodeKontrakMulai'] == 'null'){
            $createMobil['periodeKontrakMulai'] = NULL;
        }
        if($createMobil['periodeKontrakAkhir'] == 'null'){
            $createMobil['periodeKontrakAkhir'] = NULL;
        }
        $err_message = array(array('Gambar Mobil Harus Terisi'));
        if($createMobil['gambarMobil'] == 'null'){
            return response(['message' => $err_message], 400); //return eror invalid input
        }


        $gambarMobil = $request->gambarMobil->store('gambar_mobil', ['disk' => 'public']);

        $mobil = Mobil::create([
            'idMitra' => $createMobil['idMitra'],
            'namaMobil' => $createMobil['namaMobil'],
            'tipeMobil' => $createMobil['tipeMobil'],
            'jenisTransmisi' => $createMobil['jenisTransmisi'],
            'jenisBahanBakar' => $createMobil['jenisBahanBakar'],
            'volumeBahanBakar' => $createMobil['volumeBahanBakar'],
            'warnaMobil' => $createMobil['warnaMobil'],
            'kapasitasPenumpang' => $createMobil['kapasitasPenumpang'],
            'fasilitas' => $createMobil['fasilitas'],
            'platNomor' => $createMobil['platNomor'],
            'nomorSTNK' => $createMobil['nomorSTNK'],
            'kategoriAset' => $createMobil['kategoriAset'],
            'hargaSewaMobil' => $createMobil['hargaSewaMobil'],
            'statusKetersediaanMobil' => 1,
            'tanggalTerakhirServis' => $createMobil['tanggalTerakhirServis'],
            'gambarMobil' => $gambarMobil,
            'periodeKontrakMulai' => $createMobil['periodeKontrakMulai'],
            'periodeKontrakAkhir' => $createMobil['periodeKontrakAkhir'],
        ]);
        return response([
            'message' => 'Add Mobil Success',
            'data' => $mobil
        ], 200); // return data berupa json
    }

    public function update(Request $request, $id){
        $mobil = Mobil::where('idMobil' , '=', $id)->first(); // mencari data berdasarkan id

        $err_message = array(array('Mobil Not Found'));
        if(is_null($mobil)){
            return response([
                'message' => $err_message,
                'data' => null
            ], 400); //Mobil not Found
        }
        
        $updateMobil = $request->all();    
        $validate = Validator($updateMobil, [
            'namaMobil' => 'required|max:60',
            'tipeMobil' => 'required|max:20',
            'jenisTransmisi' => 'required|max:20',
            'jenisBahanBakar' => 'required|max:20',
            'volumeBahanBakar' => 'required|numeric',
            'warnaMobil' => 'required|max:20',
            'kapasitasPenumpang' => 'required|max:20|numeric',
            'fasilitas' => 'required|max:60',
            'platNomor' => 'required',
            'nomorSTNK' => 'required|numeric',
            'kategoriAset' => 'required|max:20',
            'hargaSewaMobil' => 'required|numeric',
            'statusKetersediaanMobil' => 'required',
            'tanggalTerakhirServis' => 'required|date',
            'gambarMobil' => 'max:1024|mimes:jpg,png,jpeg|image',
            'periodeKontrakAkhir' => 'after_or_equal:periodeKontrakMulai'
        ]);// validai inputan

        $err_message = array(array('Pastikan Semua Field Terisi'));
        if($updateMobil['namaMobil'] == null || $updateMobil['tipeMobil'] == null || $updateMobil['jenisTransmisi'] == null || 
            $updateMobil['jenisBahanBakar'] == null || $updateMobil['volumeBahanBakar'] == null || $updateMobil['warnaMobil'] == null || 
            $updateMobil['kapasitasPenumpang'] == null || $updateMobil['fasilitas'] == null || $updateMobil['platNomor'] == null || 
            $updateMobil['nomorSTNK'] == null || $updateMobil['kategoriAset'] == null || $updateMobil['hargaSewaMobil'] == null ||
            $updateMobil['tanggalTerakhirServis'] == null){
                return response(['message' => $err_message], 400); //return eror invalid input
        }

        if($updateMobil['kategoriAset'] == 'Sewa'){
            if($updateMobil['periodeKontrakMulai'] == 'null'){
                $err_message = array(array('Tanggal Mulai Kontrak Harus Terisi'));
                return response(['message' => $err_message], 400); //return eror invalid input
            }
            if($updateMobil['periodeKontrakAkhir'] == 'null'){
                $err_message = array(array('Tanggal Selesai Kontrak Harus Terisi'));
                return response(['message' => $err_message], 400); //return eror invalid input
            }
            if($updateMobil['idMitra'] == 'null'){
                $err_message = array(array('Nama Mitra Harus Terisi'));
                return response(['message' => $err_message], 400); //return eror invalid input
            }
        }
        
        if($updateMobil['idMitra'] == 'null'){
            $updateMobil['idMitra'] = NULL;
        }
        if($updateMobil['periodeKontrakMulai'] == '1900-01-01' || $updateMobil['periodeKontrakMulai'] == 'null'){
            $updateMobil['periodeKontrakMulai'] = NULL;
        }
        if($updateMobil['periodeKontrakAkhir'] == '1900-01-01' || $updateMobil['periodeKontrakAkhir'] == 'null'){
            $updateMobil['periodeKontrakAkhir'] = NULL;
        }
        if($validate->fails())
            return response(['message' => $validate->errors()], 400);// if validate errors
        
        if(isset($request->gambarMobil)) {
            $gambarMobil = $request->gambarMobil->store('gambar_mobil', ['disk' => 'public']);
            $mobil->gambarMobil = $gambarMobil;
        }
            
        //menimpa data
        $mobil->idMitra = $updateMobil['idMitra'];
        $mobil->namaMobil = $updateMobil['namaMobil'];
        $mobil->tipeMobil = $updateMobil['tipeMobil'];
        $mobil->jenisTransmisi = $updateMobil['jenisTransmisi'];
        $mobil->jenisBahanBakar = $updateMobil['jenisBahanBakar'];
        $mobil->volumeBahanBakar = $updateMobil['volumeBahanBakar'];
        $mobil->warnaMobil = $updateMobil['warnaMobil'];
        $mobil->kapasitasPenumpang = $updateMobil['kapasitasPenumpang'];
        $mobil->fasilitas = $updateMobil['fasilitas'];
        $mobil->platNomor = $updateMobil['platNomor'];
        $mobil->nomorSTNK = $updateMobil['nomorSTNK'];
        $mobil->kategoriAset = $updateMobil['kategoriAset'];
        $mobil->hargaSewaMobil = $updateMobil['hargaSewaMobil'];
        $mobil->statusKetersediaanMobil = 1;
        $mobil->tanggalTerakhirServis = $updateMobil['tanggalTerakhirServis'];
        $mobil->periodeKontrakMulai = $updateMobil['periodeKontrakMulai'];
        $mobil->periodeKontrakAkhir = $updateMobil['periodeKontrakAkhir'];

        if($mobil->save()){
            return response([
                'message' => 'Update Mobil Success',
                'data' => $mobil
            ], 200);
        }

        $err_message = array(array('Update Mobil Failed'));
        return response([
            'message' => $err_message,
            'data' => null
        ], 400);
    }

    public function updateStatus(Request $request, $id){
        $mobil = Mobil::where('idMobil' , '=', $id)->first(); // mencari data berdasarkan id

        $err_message = array(array('Mobil Not Found'));
        if(is_null($mobil)){
            return response([
                'message' => $err_message,
                'data' => null
            ], 400); //Mobil not Found
        }
        
        $updateMobil = $request->all();    
        $validate = Validator($updateMobil, [
            'statusKetersediaanMobil' => 'required',
        ]);// validai inputan 

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);// if validate errors
            
        //menimpa data
        $mobil->statusKetersediaanMobil = $updateMobil['statusKetersediaanMobil'];

        if($mobil->save()){
            return response([
                'message' => 'Update Status Mobil Success',
                'data' => $mobil
            ], 200);
        }

        $err_message = array(array('Update Status Mobil Failed'));
        return response([
            'message' => $err_message,
            'data' => null
        ], 400);
    }

    public function destroy($id){
        $mobil = Mobil::where('idMobil' , '=', $id)->first(); // mencari data berdasarkan id

        $err_message = array(array('Mobil Not Found'));
        if(is_null($mobil)){
            return response([
                'message' => $err_message,
                'data' => null
            ], 404);
        }//return null, data tidak ditemukan

        if($mobil->delete()){
            return response([
                'message' => 'Delete Mobil Success',
                'data' => $mobil
            ], 200);
        }//berhasil delete data

        $err_message = array(array('Delete Mobil Failed'));
        return response([
            'message' => $err_message,
            'data' => null
        ], 400);
    }
}