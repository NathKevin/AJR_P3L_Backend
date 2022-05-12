<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi;
use Validator; 
use Illuminate\Support\Str;
use Carbon\Carbon; //library time
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    public function index(){
        $transaksi = Transaksi::all();
    
        if(count($transaksi)>0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $transaksi
            ], 200);
        }//return semua data
    
        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); //data empty
    }

    public function show(Request $request, $idTransaksi){
        $transaksi = Transaksi::where('idTransaksi' , '=', $idTransaksi)->first(); // mencari data berdasarkan id

        if(!is_null($transaksi)){
            return response([
                'message' => 'Retrieve Transaksi Success',
                'data' => $transaksi
            ], 200);// Found
        }

        return response([
            'message' => 'Transaksi Not Found',
            'data' => null
        ], 400);// not Found
    }

    public function showTransaksiInProgress(Request $request, $idCustomer){
        $transaksi = Transaksi::where('idCustomer' , '=', $idCustomer)
                    ->join('pembayarans', 'pembayarans.idPembayaran', '=', 'transaksis.idPembayaran')
                    ->where('statusTransaksi', '=', 'Peminjaman Berlangsung')
                    ->first(); // mencari data berdasarkan id

        if(!is_null($transaksi)){
            return response([
                'message' => 'Retrieve Transaksi Success',
                'data' => $transaksi
            ], 200);// Found
        }

        return response([
            'message' => 'Transaksi Not Found',
            'data' => null
        ], 200);// not Found
    }

    public function showAllByCustomer(Request $request, $idCustomer){
        $start = Carbon::now()->format('ymd');
        $transaksi = Transaksi::selectRaw("*, DATEDIFF(transaksis.tanggalWaktuSelesai, $start) as diff")
            ->where('transaksis.idCustomer', '=', $idCustomer)
            ->get();

        if(count($transaksi)>0){
            return response([
                'message' => 'Retrieve Transaksi by Customer Success',
                'data' => $transaksi
            ], 200);// Found
        }

        return response([
            'message' => 'Transaksi Not Found',
            'data' => null
        ], 200);// not Found
    }

    public function create(Request $request){
        $createTransaksi = $request->all();
        $validate = Validator::make($createTransaksi, [
            'idCustomer' => 'required',
            'idPembayaran' => 'required',
            'tanggalWaktuSewa' => 'required|date',
            'tanggalWaktuSelesai' => 'required|date',
        ]);// validai inputan

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);// if validate errors

        $last_transaksi = DB::table('transaksis')->latest('idTransaksi')->first();
        $substr_id = Str::substr((string)$last_transaksi->idTransaksi, 12);
        $new_id = (int)$substr_id + 1;
        $generateNumId = Str::padLeft((string)$new_id, 3, '0');

        $registerDate = Carbon::now()->format('ymd');
        $tanggalTransakasi = Carbon::now();

        if(!is_null($createTransaksi['idDriver'])){
            $jenisTransaksi = '01';
        }else{
            $jenisTransaksi = '00';
        }

        $transaksi = Transaksi::create([
            'idTransaksi' => 'TRN'.$registerDate.$jenisTransaksi.'-'.$generateNumId,
            'idPegawai' => $createTransaksi['idPegawai'],
            'idCustomer' => $createTransaksi['idCustomer'],
            'idPembayaran' => $createTransaksi['idPembayaran'],
            'idDriver' => $createTransaksi['idDriver'],
            'tanggalTransaksi' => $tanggalTransakasi,
            'tanggalWaktuSewa' => $createTransaksi['tanggalWaktuSewa'],
            'tanggalWaktuSelesai' => $createTransaksi['tanggalWaktuSelesai'],
            'tanggalWaktuKembali' => $createTransaksi['tanggalWaktuKembali'],
            'statusTransaksi' => 'Peminjaman Berlangsung',
        ]);
        return response([
            'message' => 'Add Transaksi Success',
            'data' => $transaksi
        ], 200); // return data berupa json
    }

    public function cekTanggalSewa(Request $request){
        $data = $request->all();
        $validate = Validator::make($data, [
            'tanggalWaktuSelesai' => 'after:tanggalWaktuSewa'
        ]);// validai inputan

        if($validate->fails())
            return response(['message' => array(array('Tanggal Sewa Invalid'))], 400);// if validate errors
        
        return response([
            'message' => 'Date Valid',
        ], 200); // return data berupa json
    }

    public function countCustomer(){
        $order_count_customer = DB::table('transaksis')
                                ->join('users' , 'transaksis.idCustomer' , '=', 'users.idCustomer')
                                ->select('users.namaCustomer', DB::raw('count(*) as totalTransaksi'))
                                ->groupBy('users.namaCustomer')
                                ->orderBy('totalTransaksi')
                                ->get();
        
        if(is_null($order_count_customer)){
            return response([
                'message' => 'Empty',
                'data' => null
            ], 400);
        }

        return response([
            'message' => 'Count Customer Transaksi Success',
            'data' => $order_count_customer,
        ], 200);
    }

    public function countDriver(){
        $order_count_driver = DB::table('transaksis')
                                ->join('drivers' , 'transaksis.idDriver' , '=', 'drivers.idDriver')
                                ->select('drivers.namaDriver', DB::raw('count(*) as totalTransaksi'))
                                ->groupBy('drivers.namaDriver')
                                ->orderBy('totalTransaksi')
                                ->get();
        
        if(is_null($order_count_driver)){
            return response([
                'message' => 'Empty',
                'data' => null
            ], 400);
        }

        return response([
            'message' => 'Count Customer Transaksi Success',
            'data' => $order_count_driver,
        ], 200);
    }

    public function update(Request $request, $idTransaksi){
        $transaksi = Transaksi::where('idTransaksi' , '=', $idTransaksi)->first(); // mencari data berdasarkan id

        if(is_null($transaksi)){
            return response([
                'message' => 'Transaksi not Found',
                'data' => null
            ], 400); // not Found
        }
        
        $updateTransaksi = $request->all();    
        $validate = Validator($updateTransaksi, [
            'idPegawai' => 'required',
            'idCustomer' => 'required',
            'idPembayaran' => 'required',
            'idDriver' => 'required',
            'tanggalWaktuSewa' => 'required|date',
            'tanggalWaktuSelesai' => 'required|date',
        ]);// validai inputan 

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);// if validate errors
            
        //menimpa data
        $transaksi->idPegawai = $updateTransaksi['idPegawai'];
        $transaksi->idCustomer = $updateTransaksi['idCustomer'];
        $transaksi->idPembayaran = $updateTransaksi['idPembayaran'];
        $transaksi->idDriver = $updateTransaksi['idDriver'];
        $transaksi->tanggalWaktuSelesai = $updateTransaksi['tanggalWaktuSelesai'];
        $transaksi->tanggalWaktuSewa = $updateTransaksi['tanggalWaktuSewa'];
        $transaksi->tanggalWaktuKembali = $updateTransaksi['tanggalWaktuKembali'];

        if($transaksi->save()){
            return response([
                'message' => 'Update Transaksi Success',
                'data' => $transaksi
            ], 200);
        }

        return response([
            'message' => 'Update Transaksi Failed',
            'data' => null
        ], 400);
    }

    public function updateStatus(Request $request, $idTransaksi){
        $transaksi = Transaksi::where('idTransaksi' , '=', $idTransaksi)->first(); // mencari data berdasarkan id

        if(is_null($transaksi)){
            return response([
                'message' => 'Transaksi not Found',
                'data' => null
            ], 400); // not Found
        }
        
        $updateTransaksi = $request->all();    
        $validate = Validator($updateTransaksi, [
            'statusTransaksi' => 'required|max:60',
        ]);// validai inputan 

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);// if validate errors
            
        //menimpa data
        $transaksi->statusTransaksi = $updateTransaksi['statusTransaksi'];

        if($transaksi->save()){
            return response([
                'message' => 'Update Status Transaksi Success',
                'data' => $transaksi
            ], 200);
        }

        return response([
            'message' => 'Update Status Transaksi Failed',
            'data' => null
        ], 400);
    }

    public function updateRate(Request $request, $idTransaksi){
        $transaksi = Transaksi::where('idTransaksi' , '=', $idTransaksi)->first(); // mencari data berdasarkan id

        if(is_null($transaksi)){
            return response([
                'message' => 'Transaksi not Found',
                'data' => null
            ], 400); // not Found
        }

        $updateTransaksi = $request->all();   
        $validate = Validator($updateTransaksi, [
            'rateDriver' => 'required|numeric',
            'performaDriver' => 'required|max:100',
        ]);// validai inputan  

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);// if validate errors
        
        $transaksi->rateDriver = $updateTransaksi['rateDriver'];
        $transaksi->performaDriver = $updateTransaksi['performaDriver'];

        if($transaksi->save()){
            return response([
                'message' => 'Update Rate Success',
                'data' => $transaksi
            ], 200);
        }

        return response([
            'message' => 'Update Rate Failed',
            'data' => null
        ], 400);
    }
}