<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use Validator; 

class RoleController extends Controller
{
    public function index(){
     $role = Role::all();
     
     if(count($role)>0){
         return response([
             'message' => 'Retrieve All Success',
             'data' => $role
         ], 200);
     }//return semua data

     return response([
         'message' => 'Empty',
         'data' => null
     ], 400); //data empty
    }

    public function create(Request $request){
        $createRole = $request->all();
        $validate = Validator::make($createRole, [
            'namaRole' => 'required|max:60'
        ]);//validasi inputan role

        if($validate->fails())
            return response(['message' => $validate->errors()], 400); //return eror invalid input

        $role = Role::create($createRole);
        return response([
            'message' => 'Add Role Success',
            'data' => $role
        ], 200); // return data berupa json
    }

    public function update(Request $request, $id){
        $role = Role::where('idRole' , '=', $id)->first(); // mencari data berdasarkan id
        if(is_null($role)){
            return response([
                'message' => 'Role Not Found',
                'data' => null 
            ]);
        }//data no found, return null

        $updateRole = $request->all();
        $validate = Validator::make($updateRole, [
            'namaRole' => 'required|max:60'
        ]);//validate inputan user

        if($validate->fails()){
            return response(['message' => $validate->errors()], 400); //return error invalid input
        }

        //menimpa data lama dengan data baru
        $role->namaRole = $updateRole['namaRole'];

        if($role->save()){
            return response([
                'message' => 'Update Role Success',
                'data' => $role
            ], 200);
        }

        return response([
            'message' => 'Update Role Failed',
            'data' => null
        ], 400);
        
    }

    public function show(Request $request, $id){
        $role = Role::where('idRole' , '=', $id)->first(); // mencari data berdasarkan id

        if(!is_null($role)){
            return response([
                'message' => 'Retrieve Role Success',
                'data' => $role
            ], 200);//Role Found
        }

        return response([
            'message' => 'Role Not Found',
            'data' => null
        ], 400);//Role not Found
    }

    public function destroy($id){
        $role = Role::where('idRole' , '=', $id)->first(); // mencari data berdasarkan id

        if(is_null($role)){
            return response([
                'message' => 'Role Not Found',
                'data' => null
            ], 404);
        }//return null, data tidak ditemukan

        if($role->delete()){
            return response([
                'message' => 'Delete Role Success',
                'data' => $role
            ], 200);
        }//berhasil delete role

        return response([
            'message' => 'Delete Role Failed',
            'data' => null
        ], 400);
    }//gagal menghapus role

}