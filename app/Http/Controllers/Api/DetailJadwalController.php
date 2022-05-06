<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Detail_Jadwal;
use Validator; 
use Illuminate\Support\Facades\DB;

class DetailJadwalController extends Controller
{
    public function index(){
        $detailJadwal = DB::table('detail__jadwals')
                        ->join('pegawais' , 'pegawais.idPegawai' , '=', 'detail__jadwals.idPegawai')
                        ->join('jadwals' , 'jadwals.idJadwal' , '=', 'detail__jadwals.idJadwal')
                        ->select(DB::raw('*'))
                        ->orderBy('jadwals.hari')
                        ->get();
        // $detailJadwal = Detail_Jadwal::all();

        if(count($detailJadwal)>0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $detailJadwal,
            ]);
        }//all data found

        return response([
            'message' => 'Detail Jadwal Empty',
            'data' => null
        ]);//empty
    }

    public function show($hari){
        $detailJadwal = DB::table('detail__jadwals')
                        ->join('pegawais' , 'pegawais.idPegawai' , '=', 'detail__jadwals.idPegawai')
                        ->join('jadwals' , 'jadwals.idJadwal' , '=', 'detail__jadwals.idJadwal')
                        ->join('roles', 'roles.idRole', '=', 'pegawais.idRole')
                        ->select(DB::raw('idDetailJadwal, detail__jadwals.idJadwal, detail__jadwals.idPegawai, namaRole, namaPegawai, waktuMulai, waktuSelesai, hari, CONCAT(waktuMulai," - ",waktuSelesai) AS time'))
                        ->where('jadwals.hari', '=', $hari)
                        ->orderBy('jadwals.hari')
                        ->get();
        if(count($detailJadwal) > 0){
            return response([
                'message' => 'Retrieve Detail Jadwal Success',
                'data' =>$detailJadwal,
            ]);
        }//data found

        return response([
            'message' => 'Detail jadwal not found',
            'data' => null,
        ]);
    }

    public function cekShift($id){
        $jadwal = DB::table('detail__jadwals')
                        ->select(DB::raw("COUNT(idPegawai) AS count"))
                        ->where('idPegawai', '=', $id)
                        ->first();
                        
        if($jadwal->count > 5){
            return response([
                'message' => 'Shift Penuh',
                'data' => null
            ], 400);//Jadwal Found
        }

        return response([
            'message' => 'Jatah ambil shift masih tersedia',
            'data' => $jadwal
        ], 200);//Jadwal not Found
    }

    public function cekIsShift($idJadwal, $idPegawai){
        $detailJadwal = DB::table('detail__jadwals')
                        ->select('idDetailJadwal')
                        ->where('idPegawai', '=', $idPegawai)
                        ->where('idJadwal', '=', $idJadwal)
                        ->first();
        
        if($detailJadwal != null){
            return response([
                'message' => 'Pegawai sudah ambil jadwal shift ini'
            ], 400);//Jadwal Found
        }else{
            return response([
                'message' => 'Jadwal Pegawai Tersedia',
            ], 200);//Jadwal not Found
        }


    }

    public function create(Request $request){
        $dataDetailJadwal = $request->all();

        $validate = Validator::make($dataDetailJadwal, [
            'idJadwal' => 'required',
            'idPegawai' => 'required',
        ]);

        if($dataDetailJadwal['idJadwal'] == 'null' || $dataDetailJadwal['idJadwal'] == null){
            return response(['message' => 'Pastikan semua field terisi'], 400);// if validate errors
        }

        if($validate->fails()){
            return response(['message' => $validate->errors()], 400);// if validate errors
        }

        $detailJadwal = Detail_Jadwal::create($dataDetailJadwal);
        return response([
            'message' => 'Add Detail Jadwal Success',
            'data' => $detailJadwal
        ], 200); // return data berupa json

    }

    public function update(Request $request, $id){
        $detailJadwal = Detail_Jadwal::where('idDetailJadwal', '=', $id)->first();

        if(is_null($detailJadwal)){
            return response([
                'message' => 'Detail Jadwal Not Found',
                'data' => null 
            ], 400);
        }//data no found, return null

        $updateDetailJadwal = $request->all();
        $validate = Validator::make($updateDetailJadwal, [
            'idPegawai' => 'required',
            'idJadwal' => 'required',
        ]);//validate inputan user

        if($validate->fails()){
            return response(['message' => $validate->errors()], 400); //return error invalid input
        }

        //menimpa data lama dengan data baru
        $detailJadwal->idPegawai = $updateDetailJadwal['idPegawai'];
        $detailJadwal->idJadwal = $updateDetailJadwal['idJadwal'];

        if($detailJadwal->save()){
            return response([
                'message' => 'Update Detail Jadwal Success',
                'data' => $detailJadwal
            ], 200);
        }

        return response([
            'message' => 'Update Detail jadwal Failed',
            'data' => null
        ], 400);
    }

    public function destroy($id){
        $detailJadwal = Detail_Jadwal::where('idDetailJadwal', '=', $id)->first();

        if(is_null($detailJadwal)){
            return response([
                'message' => 'Detail Jadwal Not Found',
                'data' => null
            ], 404);
        }//return null, data tidak ditemukan

        if($detailJadwal->delete()){
            return response([
                'message' => 'Delete Detail Jadwal Success',
                'data' => $detailJadwal
            ], 200);
        }//berhasil delete data

        return response([
            'message' => 'Delete Detail Jadwal Failed',
            'data' => null
        ], 400);
    }//gagal menghapus data
}