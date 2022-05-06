<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\User;
use Validator; 
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserController extends Controller
{
    public function register(Request $request){
        $checkRequest = $request->all();
        $validate = Validator::make($checkRequest, [
            'namaCustomer' => 'required|max:60',
            'alamatCustomer' => 'required|max:60',
            'tanggalLahirCustomer' => 'required',
            'jenisKelaminCustomer' => 'required',
            'kategoriCustomer' => 'required',
            'email' => 'required|email:rfc,dns|unique:users|unique:drivers|unique:users',
            'password' => 'required|regex:/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/',
            'noTelpCustomer' => 'required|digits_between:10,13|regex:/^((08))/|numeric',
            'KTP' => 'required'
        ]);//validasi registrasi user baru

        if($validate->fails())
            return response(['message' => $validate->errors()], 400); // return error validasi input 
        
        $allCustomer = User::all();
        $count = count($allCustomer) + 1;
        $generateNumId = Str::of((string)$count)->padLeft(3, '0');

        $registerDate = Carbon::now()->format('ymd');
        
        if(!is_null($request->SIM)){
            $SIM = $request['SIM'];
        }else{
            $SIM = NULL;
        }

        if(!is_null($request->KP)){
            $KP = $request['KP'];
        }else{
            $KP = NULL;
        }

        if(!is_null($request->ratingAJR)){
            $ratingAJR = $request['ratingAJR'];
        }else{
            $ratingAJR = NULL;
        }

        if(!is_null($request->performaAJR)){
            $performaAJR = $request['performaAJR'];
        }else{
            $performaAJR = NULL;
        }
        
        $checkRequest['password'] = Hash::make($request->password);//enkripsi password
        $userData = User::create(['idCustomer' => 'CUS'.$registerDate.'-'.$generateNumId,
            'namaCustomer' => $request['namaCustomer'],
            'alamatCustomer' => $request['alamatCustomer'],
            'tanggalLahirCustomer' => $request['tanggalLahirCustomer'],
            'jenisKelaminCustomer' => $request['jenisKelaminCustomer'],
            'kategoriCustomer' => $request['kategoriCustomer'],
            'email' => $request['email'],
            'password' =>  $checkRequest['password'],
            'noTelpCustomer' => $request['noTelpCustomer'],
            'KTP' => $request['KTP'],
            'SIM' => $SIM,
            'KP'=> $KP,
            'ratingAJR' => $ratingAJR,
            'performaAJR' => $performaAJR,
            'statusBerkas' => 0]); //membuat user baru dengan memanggil model user
        return response([
            'message' => 'Register Success',
            'user' => $userData
        ], 200);// return data dalam bentuk json
    }

    public function updateProfile(Request $request, $id){
        $user = User::where('idCustomer' , '=', $id)->first();
        if(is_null($user)){
            return response([
                'message' => 'User Not Found',
                'data' => null
            ], 404);
        }// customer tidak ditemukan

        $updateData = $request->all();//ambil semua inputan dari user
        $validate = Validator::make($updateData, [
            'namaCustomer' => 'required|max:60',
            'alamatCustomer' => 'required|max:60',
            'tanggalLahirCustomer' => 'required',
            'jenisKelaminCustomer' => 'required',
            'kategoriCustomer' => 'required',
            'noTelpCustomer' => 'required|digits_between:10,13|regex:/^((08))/|numeric'
        ]);// validasi inputan update user

        if($validate->fails())
            return response(['message' => $validate->errors()], 400); //return error invalid input
        
        //mengedit timpa data yang lama dengan yang baru
        $user->namaCustomer = $updateData['namaCustomer'];
        $user->alamatCustomer = $updateData['alamatCustomer'];
        $user->tanggalLahirCustomer = $updateData['tanggalLahirCustomer'];
        $user->jenisKelaminCustomer = $updateData['jenisKelaminCustomer'];
        $user->kategoriCustomer = $updateData['kategoriCustomer'];
        $user->noTelpCustomer = $updateData['noTelpCustomer'];

        if($user->save()){
            return response([
                'message' => 'Update User Success',
                'data' => $user
            ], 200);
        }// return data course yang telah di edit dalam bentuk json

        return response([
            'message' => 'Update User Failed',
            'data' => null
        ], 400); //return message saat course gagal di edit
    }

    public function updateEmail(Request $request, $id){
        $user = User::where('idCustomer' , '=', $id)->first();
        if(is_null($user)){
            return response([
                'message' => 'user Not Found',
                'data' => null
            ], 404);
        }// data tidak ditemukan

        $updateData = $request->all();//ambil semua inputan dari user
        $validate = Validator::make($updateData, [
            'email' => ['required', 'email:rfc,dns', Rule::unique('users')->ignore($user), Rule::unique('drivers'), Rule::unique('pegawais')],
        ]);// validasi inputan update user

        if($validate->fails())
            return response(['message' => $validate->errors()], 400); //return error invalid input
        
        //mengedit timpa data yang lama dengan yang baru
        $user->email = $updateData['email'];

        if($user->save()){
            return response([
                'message' => 'Update Email User Success',
                'data' => $user
            ], 200);
        }// return data  yang telah di edit dalam bentuk json

        return response([
            'message' => 'Update Email User Failed',
            'data' => null
        ], 400); //return message saat course gagal di edit
    }

    public function updatePassword(Request $request, $id){
        $user = User::where('idCustomer' , '=', $id)->first();
        if(is_null($user)){
            return response([
                'message' => 'User Not Found',
                'data' => null
            ], 404);
        }// customer tidak ditemukan

        $updateData = $request->all();//ambil semua inputan dari user
        $validate = Validator::make($updateData, [
            'password' => 'required|regex:/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/'
        ]);// validasi inputan update user

        if($validate->fails())
            return response(['message' => $validate->errors()], 400); //return error invalid input
        
        $updateData['password'] = Hash::make($request->password);//enkripsi password

        //mengedit timpa data yang lama dengan yang baru
        $user->password = $updateData['password'];

        if($user->save()){
            return response([
                'message' => 'Update Password User Success',
                'data' => $user
            ], 200);
        }// return data course yang telah di edit dalam bentuk json

        return response([
            'message' => 'Update Password User Failed',
            'data' => null
        ], 400); //return message saat course gagal di edit
    }

    public function show($id){
        $user = User::where('idCustomer' , '=', $id)->first(); // mencari data berdasarkan id

        if(!is_null($user)){
            return response([
                'message' => 'Retrieve User Success',
                'data' => $user
            ], 200);
        } //return data yang ditemukan dalam bentuk json

        return response([
            'message' => 'User Not Found',
            'data' => null
        ], 400); //return message data tidak ditemukan
    }
}