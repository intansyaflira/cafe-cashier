<?php

namespace App\Http\Controllers\Api;

use App\Models\transaksiModel;
use App\Models\detail_transaksiModel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    public function createtransaksi(Request $request)
    {
        $validator= Validator::make($request->all(), [
            'tgl_transaksi'=>'required',
            'id_user'=>'required',
            'id_meja'=>'required',
            'nama_pelanggan'=>'required',
            'status'=>'required',
            'detail' => 'required'
        ]);
        if($validator->fails()) {
            return Response()->json($validator->errors());
        }
        $transaksi = new transaksiModel();
        $transaksi->id_user = $request->id_user;
        $transaksi->id_meja = $request->id_meja;
        $transaksi->nama_pelanggan = $request->nama_pelanggan;
        $transaksi->status = $request->status;
        $transaksi->tgl_transaksi = $request->tgl_transaksi;
        $transaksi->save();

        $id_transaksi = $transaksi->id_transaksi;
        //insert detail transaksi
        for($i = 0; $i < count($request->detail); $i++){
            $detail_transaksi = new detail_transaksiModel();
            $detail_transaksi->id_transaksi = $id_transaksi;
            $detail_transaksi->id_menu = $request->detail[$i]['id_menu'];
            $detail_transaksi->qty = $request->detail[$i]['qty'];
            $detail_transaksi->save();
        }

        if($transaksi && $detail_transaksi){
            return response()->json([
                'status' => 1,
                'message' => 'Succes add data!',
                'data' => $transaksi
            ]);
        } else {
            return Response() -> json([
                'status' => 0,
                'message' => 'Failed!'
            ]);
        }
    }

    public function gettransaksi(){
        $data = DB::table('transaksi_tabel')
        ->join('users','transaksi_tabel.id_user','=','users.id_user')
        ->join('meja','transaksi_tabel.id_meja','=','meja.id_meja')
        ->select('transaksi_tabel.id_transaksi','transaksi_tabel.tgl_transaksi','users.id_user','meja.id_meja','transaksi_tabel.nama_pelanggan','transaksi_tabel.status')
        ->get();
        return Response()->json($data);
    }
    
    public function getdetail($id)
    {
        if(transaksiModel::where('id_transaksi', $id)->exists()){
            $data_transaksi = DB::table('transaksi_tabel')
            ->join('users','transaksi_tabel.id_user','=','users.id_user')
            ->join('meja','transaksi_tabel.id_meja','=','meja.id_meja')
            ->select('transaksi_tabel.id_transaksi','transaksi_tabel.tgl_transaksi','users.id_user','meja.id_meja','transaksi_tabel.nama_pelanggan','transaksi_tabel.status')
            ->where('transaksi_tabel.id_transaksi','=',$id)
            ->get();
            return Response()->json($data_transaksi);
        }else{
            return Response()->json(['message' => 'Tidak Ditemukan']);
        }
    }

    public function get(Request $request){
        $get=transaksiModel::when($request->search, function ($query, $search) {
                return $query->where('tgl_transaksi', 'like', "%{$search}%")
                ->orWhere('status', 'like', "%{$search}%");
            })
            ->get();
        return response()->json($get);
    }

    public function deletetransaksi($id)
    {
        $hapus = transaksiModel::where('id_transaksi', $id)->delete();

        if($hapus) {
            return response()->json([
                'status' => 0,
                'message' => 'Sukses menghapus data',
                'data' => $hapus
            ]);
        }

        else {
            return response()->json([
                'status' => 1,
                'message' => 'Sukses menghapus data',
                'data' => $hapus
            ]);
        }
    }

    public function updatetransaksi($id, Request $request) {         
        $validator=Validator::make($request->all(),         
        [   
            'status'=>'required',      
        ]); 

        if($validator->fails()) {             
            return Response()->json($validator->errors()->toJson());         
        } 

        $ubah = transaksiModel::where('id_transaksi', $id)->update([             
            'status' =>$request->get('status'),
        ]); 

        if($ubah) {
            return response()->json([
                'status' => 1,
                'message' => 'Sukses mengubah data',
                'data' => $ubah
            ]);
        } else {
            return Response ()->json([
                'status' => 0,
                'message' => 'Gagal mengubah data',
                'data' => $ubah
            ]);
        }    
    }
}
