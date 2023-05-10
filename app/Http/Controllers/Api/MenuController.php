<?php

namespace App\Http\Controllers\Api;

use App\Models\menuModel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    public function get(Request $request){
        $get=menuModel::when($request->search, function ($query, $search) {
                return $query->where('jenis', 'like', "%{$search}%")
                ->orWhere('harga', 'like', "%{$search}%");
            })
            ->get();
        return response()->json($get);
    }
    
    public function getmenu()
    {
        $dt_menu=menuModel::get();
        return response()->json($dt_menu);
    }

    public function getdetail($id)
    {
        if(menuModel::where('id_menu', $id)->exists()){
            $data_menu= menuModel::select('nama_menu', 'jenis', 'deskripsi', 'harga')->where('id_menu', '=', $id)->get();
            return Response()->json($data_menu);
        }
        else{
            return Response()->json(['message' => 'Tidak ditemukan']);
        }
    }

    public function createmenu(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'nama_menu' => 'required',
            'jenis' => 'required',
            'deskripsi' => 'required',
            'image' => 'required',
            'harga' => 'required',
        ]);
        if($validator->fails()){
            return Response()->json($validator->errors()->toJson());
        }
        $save = menuModel::create([
            'nama_menu' =>$request->get('nama_menu'),
            'jenis' =>$request->get('jenis'),
            'deskripsi' =>$request->get('deskripsi'),
            'image' =>$request->get('image'),
            'harga' =>$request->get('harga'),
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

    public function updatemenu($id, Request $request) {         
        $validator=Validator::make($request->all(),         
        [   
            'nama_menu' => 'required',
            'jenis' => 'required',
            'deskripsi' => 'required',
            'image' => 'required',
            'harga' => 'required',        
        ]); 

        if($validator->fails()) {             
            return Response()->json($validator->errors()->toJson());         
        } 

        $ubah = menuModel::where('id_menu', $id)->update([             
            'nama_menu' =>$request->get('nama_menu'),
            'jenis' =>$request->get('jenis'),
            'deskripsi' =>$request->get('deskripsi'),
            'image' =>$request->get('image'),
            'harga' =>$request->get('harga'),
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

    public function deletemenu($id)
    {
        $hapus = menuModel::where('id_menu', $id)->delete();

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

    //upload foto menu
    public function upload_foto(Request $request, $id_menu){
        $validator=Validator::make($request->all(),
        [
            'foto_menu' => 'required|mimes:jpeg,png,jpg|max:2048',
        ]);
        if($validator->fails()) {
            return Response()->json($validator->errors());
        }

        //define nama file yang akan di upload
        $imageName = time().'.'.$request->foto_menu->extension();

        // proses upload
        $request->foto_menu->move(public_path('images'), $imageName);
        // $path = $request->cover_menu->storeAs('images', 'filename.jpg');

        $update=DB::table('menu')
            ->where('id_menu', '=', $id_menu)
            ->update([
                'image' => $imageName
        ]);

        $data_menu = menuModel::where('id_menu', '=', $id_menu)-> get();
        if($update){
            return Response() -> json([
                'status' => 1,
                'message' => 'Sukses mengupload foto menu!',
                'data' => $data_menu
            ]);
        } else 
        {
            return Response() -> json([
                'status' => 0,
                'message' => 'gagal mengupload foto menu!'
            ]);
        }
    }

}
