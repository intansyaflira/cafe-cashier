<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function login()
    {
        $credentials = request(['username', 'password']);
        if (!$token = auth()->guard('admin_api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

     /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth('admin_api')->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth('admin_api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('admin_api')->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->guard('admin_api')->factory()->getTTL() * 60
        ]);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_user' => 'required|string|between:2,100',
            'role' => 'required|string|between:2,100', 
            'username' => 'required|string|between:2,100',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password)]
        ));

        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user
        ], 201);
    }

    public function getuser()
    {
        $dt_user=User::get();
        return response()->json($dt_user);
    }

    public function getdetail($id)
    {
        if(User::where('id_user', $id)->exists()){
            $data_menu= User::select('nama_user', 'role', 'username')->where('id_user', '=', $id)->get();
            return Response()->json($data_menu);
        }
        else{
            return Response()->json(['message' => 'Tidak ditemukan']);
        }
    }

    public function get(Request $request){
        $get=User::when($request->search, function ($query, $search) {
                return $query->where('role', 'like', "%{$search}%");
            })
            ->get();
        return response()->json($get);
    }

    public function updateuser($id, Request $request) {         
        $validator=Validator::make($request->all(),         
        [   
            'nama_user' => 'required',
            'role' => 'required',
            'username' => 'required',
            'password' => 'required',      
        ]); 

        if($validator->fails()) {             
            return Response()->json($validator->errors()->toJson());         
        } 

        $ubah = User::where('id_user', $id)->update([             
            'nama_user' =>$request->get('nama_user'),
            'role' =>$request->get('role'),
            'username' =>$request->get('username'),
            'password' =>$request->get('password'),
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

    public function deleteuser($id)
    {
        $hapus = User::where('id_user', $id)->delete();

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
