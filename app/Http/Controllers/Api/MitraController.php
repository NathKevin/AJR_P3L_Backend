<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mitra;
use Validator; 

class MitraController extends Controller
{
    public function index(){
        $mitra = Mitra::all();
        
        if(count($mitra)>0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $mitra
            ], 200);
        }//return semua data
    
        $err_message = array(array('Empty'));
        return response([
            'message' => $err_message,
            'data' => null
        ], 400); //data empty
    }

    public function showMitraByStatus(){
        $mitra = Mitra::where('isActive' , '=', 1)->get();
    
        if(count($mitra)>0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $mitra
            ], 200);
        }//return semua data
    
        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); //data empty
    }

    public function create(Request $request){
        $createMitra = $request->all();
        $validate = Validator::make($createMitra, [
            'namaMitra' => 'required|max:60',
            'noKTPMitra' => 'required|numeric|digits:16',
            'alamatMitra' => 'required|max:60',
            'noTelpMitra' => 'required|digits_between:10,13|numeric|regex:/^((08))/',
        ]);// validai inputan Mitra

        $err_message = array(array('Pastikan Field Terisi Semua'));
        if($createMitra['namaMitra'] == 'null' || $createMitra['noKTPMitra'] == 'null' || $createMitra['alamatMitra'] == 'null' ||
            $createMitra['noTelpMitra'] == 'null'){
                return response(['message' => $err_message], 400);// if validate errors    
            }

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);// if validate errors
        
        $mitra = Mitra::create([
            'namaMitra' => $createMitra['namaMitra'],
            'noKTPMitra' => $createMitra['noKTPMitra'],
            'alamatMitra' => $createMitra['alamatMitra'],
            'noTelpMitra' => $createMitra['noTelpMitra'],
            'isActive' => 1,
        ]);
        return response([
            'message' => 'Add Mitra Success',
            'data' => $mitra
        ], 200); // return data berupa json
    }

    public function update(Request $request, $id){
        $mitra = Mitra::where('idMitra' , '=', $id)->first(); // mencari data berdasarkan id
        
        $err_message = array(array('Mitra Not Found'));
        if(is_null($mitra)){
            return response([
                'message' => $err_message,
                'data' => null
            ], 400); //Mitra not Found
        }
        
        $updateMitra = $request->all();    
        $validate = Validator($updateMitra, [
            'namaMitra' => 'required|max:60',
            'noKTPMitra' => 'required|numeric|digits:16',
            'alamatMitra' => 'required|max:60',
            'noTelpMitra' => 'required|digits_between:10,13|numeric|regex:/^((08))/',
        ]);// validai inputan Mitra
        
        $err_message = array(array('Pastikan Field Terisi Semua'));
        if($updateMitra['namaMitra'] == 'null' || $updateMitra['noKTPMitra'] == 'null' || $updateMitra['alamatMitra'] == 'null' ||
            $updateMitra['noTelpMitra'] == 'null' ){
                return response(['message' => $err_message], 400);// if validate errors    
            }

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);// if validate errors
            
        //menimpa data
        $mitra->namaMitra = $updateMitra['namaMitra'];
        $mitra->noKTPMitra = $updateMitra['noKTPMitra'];
        $mitra->alamatMitra = $updateMitra['alamatMitra'];
        $mitra->noTelpMitra = $updateMitra['noTelpMitra'];

        if($mitra->save()){
            return response([
                'message' => 'Update Mitra Success',
                'data' => $mitra
            ], 200);
        }

        $err_message = array(array('Update Mitra Failed'));
        return response([
            'message' => $err_message,
            'data' => null
        ], 400);
    }

    public function updateStatus(Request $request, $id){
        $Mitra = Mitra::where('idMitra' , '=', $id)->first(); // mencari data berdasarkan id

        $err_message = array(array('Mitra not Found'));
        if(is_null($Mitra)){
            return response([
                'message' => $err_message,
                'data' => null
            ], 400); //Mitra not Found
        }
        
        $updateMitra = $request->all();    
        $validate = Validator($updateMitra, [
            'isActive' => 'required',
        ]);// validasi inputan 

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);// if validate errors
            
        //menimpa data
        $Mitra->isActive = $updateMitra['isActive'];

        if($Mitra->save()){
            return response([
                'message' => 'Update Status Mitra Success',
                'data' => $Mitra
            ], 200);
        }

        $err_message = array(array('Update Status Mitra Failed'));
        return response([
            'message' => $err_message,
            'data' => null
        ], 400);
    }
    
    public function show(Request $request, $id){
        $mitra = Mitra::where('idMitra' , '=', $id)->first(); // mencari data berdasarkan id

        if(!is_null($mitra)){
            return response([
                'message' => 'Retrieve Mitra Success',
                'data' => $mitra
            ], 200);//Mitra Found
        }

        $err_message = array(array('Mitra Not Found'));
        return response([
            'message' => $err_message,
            'data' => null
        ], 400);//Mitra not Found
    }

    public function destroy($id){
        $mitra = Mitra::where('idMitra' , '=', $id)->first(); // mencari data berdasarkan id

        $err_message = array(array('Mitra Not Found'));
        if(is_null($mitra)){
            return response([
                'message' => $err_message,
                'data' => null
            ], 404);
        }//return null, data tidak ditemukan

        if($mitra->delete()){
            return response([
                'message' => 'Delete Mitra Success',
                'data' => $mitra
            ], 200);
        }//berhasil delete data

        $err_message = array(array('Delete Mitra Failed'));
        return response([
            'message' => $err_message,
            'data' => null
        ], 400);
    }
}