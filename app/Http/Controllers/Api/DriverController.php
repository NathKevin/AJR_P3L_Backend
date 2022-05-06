<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Carbon\Carbon; //library time
use App\Models\Driver;
use Validator; 
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DriverController extends Controller
{
    public function index(){
        $driver = Driver::all();
        
        if(count($driver)>0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $driver
            ], 200);
        }//return semua data
        
        $err_message = array(array('Empty'));
        return response([
            'message' => $err_message,
            'data' => null
        ], 400); //data empty
    }

    public function show($id){
        $driver = Driver::find($id);
        if(!is_null($driver)){
            return response([
                'message' => 'Retrieve Driver Success',
                'data' => $driver
            ], 200);
        }//return 1 data driver yang ditemukan berdasarkan id

        $err_message = array(array('Driver Not Found'));
        return response([
            'message' => $err_message,
            'data' => null
        ], 400);// data tidak ditemukan return null
    }
    
    public function create(Request $request){
        $checkRequest = $request->all();
        $validate = Validator::make($checkRequest, [
            'namaDriver' => 'required|max:60',
            'alamatDriver' => 'required|max:60',
            'tanggalLahirDriver' => 'required|date',
            'jenisKelaminDriver' => 'required|max:10',
            'email' => 'required|email:rfc,dns|unique:drivers|unique:users|unique:drivers',
            'noTelpDriver' => 'required|digits_between:10,13|regex:/^((08))/|numeric',
            'bahasa' => 'required|max:60',
            'hargaSewaDriver' => 'required|numeric',
            'fotoDriver' => 'required|max:1024|mimes:jpg,png,jpeg|image',
            'fotocopySIM' => 'required|max:1024|mimes:jpg,png,jpeg|image',
            'bebasNAPZA' => 'required|max:1024|mimes:jpg,png,jpeg|image',
            'kesehatanJiwa' => 'required|max:1024|mimes:jpg,png,jpeg|image',
            'kesehatanJasmani' => 'required|max:1024|mimes:jpg,png,jpeg|image',
            'SKCK' => 'required|max:1024|mimes:jpg,png,jpeg|image',
        ]);//validasi inputan driver$driver
        
        $err_message = array(array('Pastikan Semua Field Terisi'));
        if($checkRequest['namaDriver'] == 'null' || $checkRequest['alamatDriver'] == 'null' || $checkRequest['tanggalLahirDriver'] == 'null' ||
            $checkRequest['jenisKelaminDriver'] == 'null' || $checkRequest['email'] == 'null' || $checkRequest['password'] == 'null' ||
            $checkRequest['noTelpDriver'] == 'null' || $checkRequest['bahasa'] == 'null' || $checkRequest['hargaSewaDriver'] == 'null'){
                return response(['message' => $err_message], 400); //return eror invalid input
            }
        
        if($checkRequest['fotoDriver'] == 'null'){
            $err_message = array(array('Foto Driver Harus Terisi'));
            return response(['message' => $err_message], 400); //return eror invalid input
        }
        if($checkRequest['fotocopySIM'] == 'null'){
            $err_message = array(array('File Gambar Fotocopy SIM Harus Terisi'));
            return response(['message' => $err_message], 400); //return eror invalid input
        }
        if($checkRequest['bebasNAPZA'] == 'null'){
            $err_message = array(array('File Gambar Bebas NAPZA Harus Terisi'));
            return response(['message' => $err_message], 400); //return eror invalid input
        }
        if($checkRequest['kesehatanJiwa'] == 'null'){
            $err_message = array(array('File Gambar Kesehatan Jiwa Harus Terisi'));
            return response(['message' => $err_message], 400); //return eror invalid input
        }
        if($checkRequest['kesehatanJasmani'] == 'null'){
            $err_message = array(array('File Gambar Kesehatan Jasmani Harus Terisi'));
            return response(['message' => $err_message], 400); //return eror invalid input
        }
        if($checkRequest['SKCK'] == 'null'){
            $err_message = array(array('File Gambar SKCK Harus Terisi'));
            return response(['message' => $err_message], 400); //return eror invalid input
        }

        if($validate->fails())
            return response(['message' => $validate->errors()], 400); //return eror invalid input

        $fotoDriver = $request->fotoDriver->store('foto_driver', ['disk' => 'public']);
        $fotocopySIM = $request->fotocopySIM->store('fotocopySIM_driver', ['disk' => 'public']);
        $bebasNAPZA = $request->bebasNAPZA->store('bebasNAPZA_driver', ['disk' => 'public']);
        $kesehatanJiwa = $request->kesehatanJiwa->store('kesehatanJiwa_driver', ['disk' => 'public']);
        $kesehatanJasmani = $request->kesehatanJasmani->store('kesehatanJasmani_driver', ['disk' => 'public']);
        $SKCK = $request->SKCK->store('SKCK_driver', ['disk' => 'public']);

        // $allDriver = Driver::all();
        // $count = count($allDriver) + 1;
        $last_driver = DB::table('drivers')->latest('idDriver')->first();
        $substr_id = Str::substr((string)$last_driver->idDriver, 10);
        $new_id = (int)$substr_id + 1;
        $generateNumId = Str::padLeft((string)$new_id, 3, '0');

        $registerDate = Carbon::now()->format('ymd');
        
        $checkRequest['password'] = Hash::make($request->password);//enkripsi password
        $dataDriver = Driver::create(['idDriver' => 'DRV-'.$registerDate.$generateNumId,
        'namaDriver' => $request['namaDriver'],
        'alamatDriver' => $request['alamatDriver'],
        'tanggalLahirDriver' => $request['tanggalLahirDriver'],
        'jenisKelaminDriver' => $request['jenisKelaminDriver'],
        'email' => $request['email'],
        'password' => $checkRequest['password'],
        'noTelpDriver' => $request['noTelpDriver'],
        'bahasa' => $request['bahasa'],
        'statusKetersediaanDriver' => 1,
        'hargaSewaDriver' => $request['hargaSewaDriver'],
        'rerataRating' => 0,
        'fotoDriver' => $fotoDriver,
        'fotocopySIM' => $fotocopySIM,
        'bebasNAPZA' => $bebasNAPZA,
        'kesehatanJiwa' => $kesehatanJiwa,
        'kesehatanJasmani' => $kesehatanJasmani,
        'SKCK' => $SKCK,
        'isActive' => 1,
        'statusBerkas' => 0]);
        return response([
            'message' => 'Add Driver Success',
            'data' => $dataDriver
        ], 200); // return data berupa json
    }

    public function updateProfile(Request $request, $id){
        $driver = Driver::where('idDriver' , '=', $id)->first();

        $err_message = array(array('Driver Not Found'));
        if(is_null($driver)){
            return response([
                'message' => $err_message,
                'data' => null 
            ]);
        }//data no found, return null

        $updateDriver = $request->all();
        $validate = Validator::make($updateDriver, [
            'namaDriver' => 'required|max:60',
            'alamatDriver' => 'required|max:60',
            'tanggalLahirDriver' => 'required|date',
            'jenisKelaminDriver' => 'required|max:10',
            'noTelpDriver' => 'required|digits_between:10,13|regex:/^((08))/|numeric',
            'bahasa' => 'required|max:60',
            'fotoDriver' => 'required',
        ]);//validate inputan user

        $err_message = array(array('Pastikan Semua Field Terisi'));
        if($checkRequest['namaDriver'] == 'null' || $checkRequest['alamatDriver'] == 'null' || $checkRequest['tanggalLahirDriver'] == 'null' ||
            $checkRequest['jenisKelaminDriver'] == 'null' || $checkRequest['noTelpDriver'] == 'null' || $checkRequest['bahasa'] == 'null'){
                return response(['message' => $err_message], 400); //return eror invalid input
            }

        if($validate->fails()){
            return response(['message' => $validate->errors()], 400); //return error invalid input
        }

        //menimpa data lama dengan data baru
        $driver->namaDriver = $updateDriver['namaDriver'];
        $driver->alamatDriver = $updateDriver['alamatDriver'];
        $driver->tanggalLahirDriver = $updateDriver['tanggalLahirDriver'];
        $driver->jenisKelaminDriver = $updateDriver['jenisKelaminDriver'];
        $driver->noTelpDriver = $updateDriver['noTelpDriver'];
        $driver->bahasa = $updateDriver['bahasa'];
        $driver->fotoDriver = $updateDriver['fotoDriver'];

        if($driver->save()){
            return response([
                'message' => 'Update Driver Success',
                'data' => $driver
            ], 200);
        }

        $err_message = array(array('Update Driver Failed'));
        return response([
            'message' => $err_message,
            'data' => null
        ], 400);
        
    }

    public function updateByAdmin(Request $request, $id){
        $driver = Driver::where('idDriver' , '=', $id)->first();

        $err_message = array(array('Driver Not Found'));
        if(is_null($driver)){
            return response([
                'message' => $err_message,
                'data' => null 
            ]);
        }//data no found, return null

        $updateDriver = $request->all();
        $validate = Validator::make($updateDriver, [
            'namaDriver' => 'required|max:60',
            'alamatDriver' => 'required|max:60',
            'tanggalLahirDriver' => 'required|date',
            'jenisKelaminDriver' => 'required|max:10',
            'noTelpDriver' => 'required|digits_between:10,13|regex:/^((08))/|numeric',
            'bahasa' => 'required|max:60',
            'hargaSewaDriver' => 'required|numeric',
        ]);//validate inputan user

        $err_message = array(array('Pastikan Semua Field Terisi'));
        if($updateDriver['namaDriver'] == 'null' || $updateDriver['alamatDriver'] == 'null' || $updateDriver['tanggalLahirDriver'] == 'null' ||
            $updateDriver['jenisKelaminDriver'] == 'null' || $updateDriver['noTelpDriver'] == 'null' || $updateDriver['bahasa'] == 'null' ||
            $updateDriver['hargaSewaDriver'] == 'null'){
                return response(['message' => $err_message], 400); //return eror invalid input
            }

        if($validate->fails()){
            return response(['message' => $validate->errors()], 400); //return error invalid input
        }

        //menimpa data lama dengan data baru
        $driver->namaDriver = $updateDriver['namaDriver'];
        $driver->alamatDriver = $updateDriver['alamatDriver'];
        $driver->tanggalLahirDriver = $updateDriver['tanggalLahirDriver'];
        $driver->jenisKelaminDriver = $updateDriver['jenisKelaminDriver'];
        $driver->noTelpDriver = $updateDriver['noTelpDriver'];
        $driver->bahasa = $updateDriver['bahasa'];
        $driver->hargaSewaDriver = $updateDriver['hargaSewaDriver'];
        
        if(isset($request->fotoDriver)) {
            $fotoDriver = $request->fotoDriver->store('foto_driver', ['disk' => 'public']);
            $driver->fotoDriver = $fotoDriver;
        }
        if(isset($request->fotocopySIM)) {
            $fotocopySIM = $request->fotocopySIM->store('fotocopySIM_driver', ['disk' => 'public']);
            $driver->fotocopySIM = $fotocopySIM;
        }
        if(isset($request->bebasNAPZA)) {
            $bebasNAPZA = $request->bebasNAPZA->store('bebasNAPZA_driver', ['disk' => 'public']);
            $driver->bebasNAPZA = $bebasNAPZA;
        }
        if(isset($request->kesehatanJiwa)) {
            $kesehatanJiwa = $request->kesehatanJiwa->store('kesehatanJiwa_driver', ['disk' => 'public']);
            $driver->kesehatanJiwa = $kesehatanJiwa;
        }
        if(isset($request->kesehatanJasmani)) {
            $kesehatanJasmani = $request->kesehatanJasmani->store('kesehatanJasmani_driver', ['disk' => 'public']);
            $driver->kesehatanJasmani = $kesehatanJasmani;
        }
        if(isset($request->SKCK)) {
            $SKCK = $request->SKCK->store('SKCK_driver', ['disk' => 'public']);
            $driver->SKCK = $SKCK;
        }

        if($driver->save()){
            return response([
                'message' => 'Update Driver Success',
                'data' => $driver
            ], 200);
        }

        $err_message = array(array('Update Driver Failed'));
        return response([
            'message' => $err_message,
            'data' => null
        ], 400);
        
    }

    public function updateEmail(Request $request, $id){
        $driver = Driver::where('idDriver' , '=', $id)->first();

        $err_message = array(array('Driver Not Found'));
        if(is_null($driver)){
            return response([
                'message' => $err_message,
                'data' => null
            ], 404);
        }// data tidak ditemukan

        $updateData = $request->all();//ambil semua inputan dari user
        $validate = Validator::make($updateData, [
            'email' => ['required', 'email:rfc,dns', Rule::unique('drivers')->ignore($driver), Rule::unique('users'), Rule::unique('pegawais')],
        ]);// validasi inputan update user

        $err_message = array(array('Email baru harus terisi'));
        if($updateData['email'] == null){
            return response(['message' => $err_message], 400);
        }

        if($validate->fails())
            return response(['message' => $validate->errors()], 400); //return error invalid input
        
        //mengedit timpa data yang lama dengan yang baru
        $driver->email = $updateData['email'];

        if($driver->save()){
            return response([
                'message' => 'Update Email Driver Success',
                'data' => $driver
            ], 200);
        }// return data course yang telah di edit dalam bentuk json

        $err_message = array(array('Update Email Driver Failed'));
        return response([
            'message' => $err_message,
            'data' => null
        ], 400); //return message saat course gagal di edit
    }

    public function updatePassword(Request $request, $id){
        $driver = Driver::where('idDriver' , '=', $id)->first();

        $err_message = array(array('Driver Not Found'));
        if(is_null($driver)){
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
        
        $updateData['password'] = Hash::make($request->password);//enkripsi password

        //mengedit timpa data yang lama dengan yang baru
        $driver->password = $updateData['password'];

        if($driver->save()){
            return response([
                'message' => 'Update Password Driver Success',
                'data' => $driver
            ], 200);
        }// return data course yang telah di edit dalam bentuk json

        $err_message = array(array('Update Password Driver Failed'));
        return response([
            'message' => $err_message,
            'data' => null
        ], 400); //return message saat course gagal di edit
    }

    public function updateStatusKetersediaan($id){
        $driver = Driver::where('idDriver' , '=', $id)->first();
        if(is_null($driver)){
            return response([
                'message' => 'Driver Not Found',
                'data' => null 
            ]);
        }//data no found, return null

        //menimpa data lama dengan data baru
        if($driver['statusKetersediaanDriver'] === 1){
            $driver->statusKetersediaanDriver = 0;
        }else if($driver['statusKetersediaanDriver'] === 0){
            $driver->statusKetersediaanDriver = 1;
        }

        if($driver->save()){
            return response([
                'message' => 'Update Status Driver Success',
                'data' => $driver
            ], 200);
        }

        return response([
            'message' => 'Update Status Driver Failed',
            'data' => null
        ], 400);
        
    }

    public function updateBerkas($id){
        $driver = Driver::where('idDriver' , '=', $id)->first();

        $err_message = array(array('Driver Not Found'));
        if(is_null($driver)){
            return response([
                'message' => $err_message,
                'data' => null 
            ]);
        }//data no found, return null

        //menimpa data lama dengan data baru
        if($driver['statusBerkas'] === 1){
            $driver->statusBerkas = 0;
        }else if($driver['statusBerkas'] === 0){
            $driver->statusBerkas = 1;
        }

        if($driver->save()){
            return response([
                'message' => 'Status Berhasil Diubah',
                'data' => $driver
            ], 200);
        }

        $err_message = array(array('Update Status Ketersediaan Driver Failed'));
        return response([
            'message' => $err_message,
            'data' => null
        ], 400);
        
    }

    public function updateStatusAktif($id){
        $driver = Driver::where('idDriver' , '=', $id)->first();

        $err_message = array(array('Driver Not Found'));
        if(is_null($driver)){
            return response([
                'message' => $err_message,
                'data' => null 
            ]);
        }//data no found, return null

        //menimpa data lama dengan data baru
        if($driver['isActive'] === 1){
            $driver->isActive = 0;
        }else if($driver['isActive'] === 0){
            $driver->isActive = 1;
        }

        if($driver->save()){
            return response([
                'message' => 'Status Aktif Driver Updated',
                'data' => $driver
            ], 200);
        }

        $err_message = array(array('Update Status Aktif Driver Failed'));
        return response([
            'message' => $err_message,
            'data' => null
        ], 400);
        
    }

    // FUNGSI UPDATE RERATA//
    /*Keterangan : diperlukannya transaksi yang sudah memiliki rating driver
      TODO : 
      1. get all transaksi from {id}
      2. count all transaksi from get
      3. sum all rating driver from get transaksi
      4. divide sum and count
      5. save */

    public function destroy($id){
        $driver = Driver::where('idDriver' , '=', $id)->first();

        $err_message = array(array('Driver Not Found'));
        if(is_null($driver)){
            return response([
                'message' => $err_message,
                'data' => null
            ], 404);
        }//return null, data tidak ditemukan

        if($driver->delete()){
            return response([
                'message' => 'Delete Driver Success',
                'data' => $driver
            ], 200);
        }//berhasil delete data

        $err_message = array(array('Delete Driver Failed'));
        return response([
            'message' => $err_message,
            'data' => null
        ], 400);
    }//gagal menghapus data
}