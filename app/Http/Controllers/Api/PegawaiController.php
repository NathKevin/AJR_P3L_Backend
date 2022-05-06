<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pegawai;
use Validator; 
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class PegawaiController extends Controller
{
    public function index(){
        $pegawai = DB::table('pegawais')
                                ->join('roles' , 'roles.idRole' , '=', 'pegawais.idRole')
                                ->select(DB::raw('*'))
                                ->orderBy('roles.idRole')
                                ->get();
        //$pegawai = Pegawai::all();

        if(count($pegawai)>0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $pegawai
            ], 200);
        }//return semua data
   
        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); //data empty
    }

    public function getPegawaiAktif(){
        $pegawai = DB::table('pegawais')
                                ->join('roles' , 'roles.idRole' , '=', 'pegawais.idRole')
                                ->select(DB::raw('*'))
                                ->where('isActive', '=', 1)
                                ->get();
        //$pegawai = Pegawai::all();

        if(count($pegawai)>0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $pegawai
            ], 200);
        }//return semua data
   
        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); //data empty
    }

    public function show($id){
        $pegawai = Pegawai::where('idPegawai', '=', $id)->first();//get pegawai with id

        if(!is_null($pegawai)){
            return response([
                'message' => 'Retrieve Pegawai Success',
                'data' => $pegawai
            ], 200);
        }//data found

        return response([
            'message' => 'Pegawai not found',
            'data' => null
        ], 200); //data not found
    }

    public function create(Request $request){
        $dataPegawai = $request->all();
        $validate = Validator::make($dataPegawai, [
            'idRole' => 'required',
            'namaPegawai' => 'required|max:60',
            'alamatPegawai' => 'required|max:60',
            'tanggalLahirPegawai' => 'required|date',
            'jenisKelaminPegawai' => 'required',
            'email' => 'required|email:rfc,dns|unique:Pegawais|unique:users|unique:pegawais',
            'password' => 'required',
            'noTelpPegawai' => 'required|digits_between:10,13|regex:/^((08))/|numeric',
        ], [],
        [
            'namaPegawai' => 'Nama Pegawai',
            'alamatPegawai' => 'Alamat Pegawai',
            'email' => 'Email Pegawai',
            'noTelpPegawai' => 'No Telepon Pegawai',
        ]);//data must be validated

        $err_message = array(array('Pastikan Field Terisi Semua')); //di array dalam array karena di frontend nanti di nestedloop

        if($dataPegawai['idRole'] == 'null' || $dataPegawai['namaPegawai'] == 'null' || $dataPegawai['alamatPegawai'] == 'null' ||
            $dataPegawai['tanggalLahirPegawai'] == 'null' || $dataPegawai['jenisKelaminPegawai'] == 'null' || $dataPegawai['email'] == 'null' || 
            $dataPegawai['noTelpPegawai'] == 'null'){
                return response([ 'message' => $err_message], 400); //validate failed
            }

        if($validate->fails()){
            return response([ 'message' => $validate->errors()], 400); //validate failed
        }
    
        if($dataPegawai['fotoPegawai'] != 'null'){
            $validateImg = Validator::make($dataPegawai , [
                'fotoPegawai' => 'required|max:1024|mimes:jpg,png,jpeg|image',
            ], [],
            [
                'fotoPegawai' => 'Foto Pegawai',
            ]);

            if($validateImg->fails()){
                return response([ 'message' => $validateImg->errors()], 400); //validate failed
            }

            $fotoPegawai = $request->fotoPegawai->store('foto_pegawai', ['disk' => 'public']);
        }else{
            $fotoPegawai = NULL;
        }

        $dataPegawai['password'] = Hash::make($request->password);//enkripsi password
        $pegawai = Pegawai::create([
            'idRole' => $dataPegawai['idRole'],
            'namaPegawai' => $dataPegawai['namaPegawai'],
            'alamatPegawai' => $dataPegawai['alamatPegawai'],
            'tanggalLahirPegawai' => $dataPegawai['tanggalLahirPegawai'],
            'jenisKelaminPegawai' => $dataPegawai['jenisKelaminPegawai'],
            'email' => $dataPegawai['email'],
            'password' => $dataPegawai['password'],
            'noTelpPegawai' => $dataPegawai['noTelpPegawai'],
            'fotoPegawai' => $fotoPegawai,
            'isActive' => 1,
        ]);

        return response([
            'message' => 'Add Pegawai Success',
            'data' => $pegawai
        ], 200); // return data berupa json

    }

    public function update(Request $request, $id){
        $pegawai = Pegawai::where('idPegawai' , '=', $id)->first(); // mencari data berdasarkan id

        $err_message = array(array('Pegawai Not Found'));
        if(is_null($pegawai)){
            return response([
                'message' => $err_message,
                'data' => null
            ], 400); //Pegawai not Found
        }
        
        $updatePegawai = $request->all();    
        $validate = Validator($updatePegawai, [
            'idRole' => 'required',
            'namaPegawai' => 'required|max:60',
            'alamatPegawai' => 'required|max:60',
            'tanggalLahirPegawai' => 'required|date',
            'jenisKelaminPegawai' => 'required',
            'noTelpPegawai' => 'required|digits_between:10,13|regex:/^((08))/|numeric',
            'fotoPegawai' => 'max:1024|mimes:jpg,png,jpeg|image',
        ]);// validasi inputan 
        
        $err_message = array(array('Pastikan Field Terisi Semua'));
        if($updatePegawai['idRole'] == 'null' || $updatePegawai['namaPegawai'] == 'null' || $updatePegawai['alamatPegawai'] == 'null' ||
            $updatePegawai['tanggalLahirPegawai'] == 'null' || $updatePegawai['jenisKelaminPegawai'] == 'null' || 
            $updatePegawai['noTelpPegawai'] == 'null'){
                return response([ 'message' => $err_message], 400); //validate failed
            }

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);// if validate errors
            
        //menimpa data
        $pegawai->idRole = $updatePegawai['idRole'];
        $pegawai->namaPegawai = $updatePegawai['namaPegawai'];
        $pegawai->alamatPegawai = $updatePegawai['alamatPegawai'];
        $pegawai->tanggalLahirPegawai = $updatePegawai['tanggalLahirPegawai'];
        $pegawai->jenisKelaminPegawai = $updatePegawai['jenisKelaminPegawai'];
        $pegawai->noTelpPegawai = $updatePegawai['noTelpPegawai'];
        if(isset($request->fotoPegawai)) {
            $fotoPegawai = $request->fotoPegawai->store('foto_pegawai', ['disk' => 'public']);
            $pegawai->fotoPegawai = $fotoPegawai;
        }

        if($pegawai->save()){
            return response([
                'message' => 'Update Pegawai Success',
                'data' => $pegawai
            ], 200);
        }

        $err_message = array(array('Update Pegawai Failed'));
        return response([
            'message' => $err_message,
            'data' => null
        ], 400);
    }

    public function updateStatus(Request $request, $id){
        $pegawai = Pegawai::where('idPegawai' , '=', $id)->first(); // mencari data berdasarkan id

        $err_message = array(array('Pegawai Not Found'));
        if(is_null($pegawai)){
            return response([
                'message' => $err_message,
                'data' => null
            ], 400); //Pegawai not Found
        }
        
        $updatePegawai = $request->all();    
        $validate = Validator($updatePegawai, [
            'isActive' => 'required',
        ]);// validasi inputan 

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);// if validate errors
            
        //menimpa data
        $pegawai->isActive = $updatePegawai['isActive'];

        if($pegawai->save()){
            return response([
                'message' => 'Update Status Pegawai Success',
                'data' => $pegawai
            ], 200);
        }

        $err_message = array(array('Update Status Pegawai Failed'));
        return response([
            'message' => $err_message,
            'data' => null
        ], 400);
    }

    public function updatePassword(Request $request, $id){
        $pegawai = Pegawai::where('idPegawai' , '=', $id)->first();

        $err_message = array(array('Pegawai Not Found'));
        if(is_null($pegawai)){
            return response([
                'message' => $err_message,
                'data' => null
            ], 404);
        }// data tidak ditemukan

        $updateData = $request->all();//ambil semua inputan dari user
        $validate = Validator::make($updateData, [
            'password' => 'required|regex:/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/'
        ]);// validasi inputan update user

        $err_message = array(array('Password Field harus terisi semua'));
        if($updateData['oldPassword'] == null || $updateData['password'] == null){
            return response(['message' => $err_message], 400);
        }

        if($validate->fails())
        return response(['message' => $validate->errors()], 400); //return error invalid input
        
        if(Hash::check($updateData['oldPassword'], $pegawai['password'])){
            $updateData['password'] = Hash::make($request->password);//enkripsi password
        }else{
            $err_message = array(array('Password lama tidak sesuai'));
            return response(['message' => $err_message], 400);
        }

        //mengedit timpa data yang lama dengan yang baru
        $pegawai->password = $updateData['password'];

        if($pegawai->save()){
            return response([
                'message' => 'Update Password Pegawai Success',
                'data' => $pegawai
            ], 200);
        }// return data course yang telah di edit dalam bentuk json

        $err_message = array(array('Update Password Pegawai Failed'));
        return response([
            'message' => $err_message,
            'data' => null
        ], 400); //return message saat course gagal di edit
    }

    public function updateEmail(Request $request, $id){
        $pegawai = Pegawai::where('idPegawai' , '=', $id)->first();

        $err_message = array(array('Pegawai Not Found'));
        if(is_null($pegawai)){
            return response([
                'message' => $err_message,
                'data' => null
            ], 404);
        }// data tidak ditemukan
        
        $updateData = $request->all();//ambil semua inputan dari user
        $validate = Validator::make($updateData, [
            'email' => ['required', 'email:rfc,dns', Rule::unique('pegawais')->ignore($pegawai), Rule::unique('users'), Rule::unique('drivers')],
        ]);// validasi inputan update user
        
        $err_message = array(array('Email baru harus terisi'));
        if($updateData['email'] == null){
            return response(['message' => $err_message], 400);
        }

        if($validate->fails())
            return response(['message' => $validate->errors()], 400); //return error invalid input
        
        //mengedit timpa data yang lama dengan yang baru
        $pegawai->email = $updateData['email'];

        if($pegawai->save()){
            return response([
                'message' => 'Update Email Pegawai Success',
                'data' => $pegawai
            ], 200);
        }// return data course yang telah di edit dalam bentuk json

        $err_message = array(array('Update Email Pegawai Failed'));
        return response([
            'message' => $err_message,
            'data' => null
        ], 400); //return message saat course gagal di edit
    }

    public function destroy($id){
        $pegawai = Pegawai::where('idPegawai' , '=', $id)->first(); // mencari data berdasarkan id

        if(is_null($pegawai)){
            return response([
                'message' => 'Pegawai Not Found',
                'data' => null
            ], 404);
        }//return null, data tidak ditemukan

        if($pegawai->delete()){
            return response([
                'message' => 'Delete Pegawai Success',
                'data' => $pegawai
            ], 200);
        }//berhasil delete data

        return response([
            'message' => 'Delete Pegawai Failed',
            'data' => null
        ], 400);
    }
}