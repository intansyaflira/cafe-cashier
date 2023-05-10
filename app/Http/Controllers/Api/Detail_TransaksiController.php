<?php

namespace App\Http\Controllers\Api;

use App\Models\transaksiModel;
use App\Models\detail_transaksiModel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class Detail_TransaksiController extends Controller
{
    public function get(){
        $data = DB::table('detail_transaksi_tabel')
        ->join('transaksi_tabel','detail_transaksi_tabel.id_transaksi','=','transaksi_tabel.id_transaksi')
        ->join('menu','detail_transaksi_tabel.id_menu','=','menu.id_menu')
        ->select('detail_transaksi_tabel.id_detail_transaksi','transaksi_tabel.id_transaksi','menu.id_menu','detail_transaksi_tabel.qty')
        ->get();
        return Response()->json($data);
    }

    public function getdetail($id)
    {
        if(detail_transaksiModel::where('id_detail_transaksi', $id)->exists()){
            $data_transaksi = DB::table('detail_transaksi_tabel')
            ->join('transaksi_tabel','detail_transaksi_tabel.id_transaksi','=','transaksi_tabel.id_transaksi')
            ->join('menu','detail_transaksi_tabel.id_menu','=','menu.id_menu')
            ->select('detail_transaksi_tabel.id_detail_transaksi','transaksi_tabel.id_transaksi','menu.id_menu','detail_transaksi_tabel.qty')
            ->where('detail_transaksi_tabel.id_detail_transaksi','=',$id)
            ->get();
            return Response()->json($data_transaksi);
        }else{
            return Response()->json(['message' => 'Tidak Ditemukan']);
        }
    }

    public function additem(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'id_transaksi' => 'required',
            'id_menu' => 'required',
            'qty' => 'required',
        ]);
        if($validator->fails()){
            return Response()->json($validator->errors()->toJson());
        }
        $save = detail_transaksiModel::create([
            'id_transaksi' =>$request->get('id_transaksi'),
            'id_menu' =>$request->get('id_menu'),
            'qty' =>$request->get('qty'),
        ]);
        if($save) {
            return response()->json([
                'status' => 1,
                'message' => 'Sukses menambahkan data',
                'data' => $save
            ]);
        } else {
            return Response ()->json([
                'status' => 0,
                'message' => 'Gagal menambahkan data',
                'data' => $save
            ]);
        }
    }
}
