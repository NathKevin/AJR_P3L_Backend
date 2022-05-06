<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jadwal;
use Validator; 
use Illuminate\Support\Facades\DB;

class JadwalController extends Controller
{
    public function index(){
        $jadwal = Jadwal::all();
    
        if(count($jadwal)>0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $jadwal
            ], 200);
        }//return semua data
    
        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); //data empty
    }

    public function getTime(){
        $jadwal = DB::table('jadwals')
                        ->select(DB::raw("waktuMulai, waktuSelesai"))
                        ->groupBy('waktuMulai', 'waktuSelesai')
                        ->get();
    
        if(count($jadwal)>0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $jadwal
            ], 200);
        }//return semua data
    
        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); //data empty
    }

    public function create(Request $request){
        $createJadwal = $request->all();
        $validate = Validator::make($createJadwal, [
            'waktuMulai' => 'required',
            'waktuSelesai' => 'required',
            'hari' => 'required|max:10'
        ]);// validai inputan Jadwal

        if($createJadwal['hari'] == 'null')
            return response(['message' => 'Hari harus terisi'], 400);// if validate errors

        if($createJadwal['waktuMulai'] == 'null')
            return response(['message' => 'Jam mulai kerja harus terisi'], 400);// if validate errors

        if($createJadwal['waktuSelesai'] == 'null')
            return response(['message' => 'Jam selesai kerja harus terisi'], 400);// if validate errors

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);// if validate errors
        
        $jadwal = Jadwal::create($createJadwal);
        return response([
            'message' => 'Add Jadwal Success',
            'data' => $jadwal
        ], 200); // return data berupa json
    }

    public function update(Request $request, $id){
        $jadwal = Jadwal::where('idJadwal' , '=', $id)->first(); // mencari data berdasarkan id

        if(is_null($jadwal)){
            return response([
                'message' => 'Jadwal not Found',
                'data' => null
            ], 400); //Jadwal not Found
        }

        $updateJadwal = $request->all();    
        $validate = Validator($updateJadwal, [
            'waktuMulai' => 'required',
            'waktuSelesai' => 'required',
            'hari' => 'required|max:10'
        ]);// validai inputan Jadwal
        
        if($updateJadwal['hari'] == 'null' || $updateJadwal['hari'] == null)
            return response(['message' => 'Hari harus terisi'], 400);// if validate errors

        if($updateJadwal['waktuMulai'] == 'null' || $updateJadwal['waktuMulai'] == null)
            return response(['message' => 'Jam mulai kerja harus terisi'], 400);// if validate errors

        if($updateJadwal['waktuSelesai'] == 'null' || $updateJadwal['waktuSelesai'] == null)
            return response(['message' => 'Jam selesai kerja harus terisi'], 400);// if validate errors

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);// if validate errors
            
        //menimpa data
        $jadwal->waktuMulai = $updateJadwal['waktuMulai'];
        $jadwal->waktuSelesai = $updateJadwal['waktuSelesai'];
        $jadwal->hari = $updateJadwal['hari'];

        if($jadwal->save()){
            return response([
                'message' => 'Update Jadwal Success',
                'data' => $jadwal
            ], 200);
        }

        return response([
            'message' => 'Update Jadwal Failed',
            'data' => null
        ], 400);
    }
    
    public function show(Request $request, $id){
        $jadwal = Jadwal::where('idJadwal' , '=', $id)->first(); // mencari data berdasarkan id

        if(!is_null($jadwal)){
            return response([
                'message' => 'Retrieve Jadwal Success',
                'data' => $jadwal
            ], 200);//Jadwal Found
        }

        return response([
            'message' => 'Jadwal Not Found',
            'data' => null
        ], 400);//Jadwal not Found
    }

    public function cekInputJadwal($hari, $waktuMulai, $waktuSelesai){

        if($hari == 'null' || $waktuMulai == 'null' || $waktuSelesai == 'null' ||
            $hari == null || $waktuMulai == null || $waktuSelesai == null){
            return response(['message' => 'Pastikan semua field terisi',
                                'data' => null,
                                'status' => 0], 
                                400);
        }

        $jadwal = DB::table('jadwals')
                        ->select(DB::raw("idJadwal"))
                        ->where('hari', '=', $hari)
                        ->where('waktuMulai', '=', $waktuMulai)
                        ->where('waktuSelesai', '=', $waktuSelesai)
                        ->first();

        if(!is_null($jadwal)){
            return response([
                'message' => 'Retrieve Jadwal Success',
                'data' => $jadwal,
                'status' => 1
            ], 200);//Jadwal Found
        }

        return response([
            'message' => 'Jadwal Tidak Tersedia',
            'data' => null,
            'status' => 0
        ], 400);//Jadwal not Found
    }

    public function destroy($id){
        $jadwal = Jadwal::where('idJadwal' , '=', $id)->first(); // mencari data berdasarkan id

        if(is_null($jadwal)){
            return response([
                'message' => 'Jadwal Not Found',
                'data' => null
            ], 404);
        }//return null, data tidak ditemukan

        if($jadwal->delete()){
            return response([
                'message' => 'Delete Jadwal Success',
                'data' => $jadwal
            ], 200);
        }//berhasil delete data

        return response([
            'message' => 'Delete Jadwal Failed',
            'data' => null
        ], 400);
    }
}