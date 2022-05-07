<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Driver;
use App\Models\Pegawai;
use Validator; 
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request){
        $loginData = $request->all();
        // $validate = Validator::make($loginData, [
        //     'email' => 'required|email:rfc,dns',
        //     'password' => 'required|regex:/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/'
        // ]); //validasi inputan user saat login

        // if($validate->fails())
        //     return response(['message' => $validate->errors()],400); // return error validasi

        if(User::where('email', '=', $loginData['email'])->first()){
            $user = User::where('email' , '=', $loginData['email'])->first();

            if(Hash::check($loginData['password'], $user['password'])){
                $token = Str::random(80);

                $user->api_token = hash('sha256', $token);
                $user->save();
                
                return response([
                    'message' => 'Customer Authenticated',
                    'user' => $user,
                    'token_type' => 'Bearer',
                    'token' => $token,
                    'role' => 'customer'
                ]); // return data user dan token dalam bentuk json
            }else{
                $err_message = array(array('Wrong Email or Password'));
                return response([
                    'message' => $err_message,
                ], 400); 
            }
        }else if(Driver::where('email', '=', $loginData['email'])->first()){
            $driver = Driver::where('email' , '=', $loginData['email'])->first();

            if(Hash::check($loginData['password'], $driver['password'])){
                $token = Str::random(80);

                $driver->api_token = hash('sha256', $token);
                $driver->save();
                
                return response([
                    'message' => 'Driver Authenticated',
                    'user' => $driver,
                    'token_type' => 'Bearer',
                    'token' => $token,
                    'role' => 'driver'
                ]); // return data user dan token dalam bentuk json
            }else{
                $err_message = array(array('Wrong Email or Password'));
                return response([
                    'message' => $err_message,
                ], 400); 
            }
        }else if(Pegawai::where('email', '=', $loginData['email'])->first()){
            $pegawai = Pegawai::where('email' , '=', $loginData['email'])->first();

            if(Hash::check($loginData['password'], $pegawai['password'])){
                $token = Str::random(80);

                $pegawai->api_token = hash('sha256', $token);
                $pegawai->save();
                
                return response([
                    'message' => 'Pegawai Authenticated',
                    'user' => $pegawai,
                    'token_type' => 'Bearer',
                    'token' => $token,
                    'role' => 'pegawai'
                ]); // return data user dan token dalam bentuk json
            }else{
                $err_message = array(array('Wrong Email or Password'));
                return response([
                    'message' => $err_message,
                ], 400); 
            }
        }else{
            $err_message = array(array('Wrong Email or Password'));
            return response([
                'message' => $err_message,
                'user' => null,
            ], 400); // return data user dan token dalam bentuk json
        }

 
    }

}