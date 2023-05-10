<?php

namespace App\Http\Controllers\Api;

use App\Models\mejaModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class MejaController extends Controller
{
    public function get(Request $request){
        $get=mejaModel::when($request->search, function ($query, $search) {
                return $query->where('status', 'like', "%{$search}%");
            })
            ->get();
        return response()->json($get);
    }

    public function getmeja()
    {
        $dt_meja=mejaModel::get();
        return response()->json($dt_meja);
    }

    public function getdetail($id)
    {
        if(mejaModel::where('id_meja', $id)->exists()){
            $data_meja= mejaModel::select('nomor_meja', 'status')->where('id_meja', '=', $id)->get();
            return Response()->json($data_meja);
        }
        else{
            return Response()->json(['message' => 'Tidak ditemukan']);
        }
    }

    public function createmeja(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'nomor_meja' => 'required',
            'status' => 'required',
        ]);
        if($validator->fails()){
            return Response()->json($validator->errors()->toJson());
        }
        $save = mejaModel::create([
            'nomor_meja' =>$request->get('nomor_meja'),
            'status' => $request->get('status'),
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

    public function updatemeja($id, Request $request) {         
        $validator=Validator::make($request->all(),         
        [   
            'nomor_meja' => 'required', 
            'status' => 'required',     
        ]); 

        if($validator->fails()) {             
            return Response()->json($validator->errors()->toJson());         
        } 

        $ubah = mejaModel::where('id_meja', $id)->update([             
            'nomor_meja' =>$request->get('nomor_meja'),
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

    public function deletemeja($id)
    {
        $hapus = mejaModel::where('id_meja', $id)->delete();

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

}
