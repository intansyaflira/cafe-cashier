<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MejaController;
use App\Http\Controllers\Api\MenuController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\TransaksiController;
use App\Http\Controllers\Api\Detail_TransaksiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/gagal_akses', function(){
    return Response()->json(['status'=>'Login Failed!']);
})->name('login');

Route::prefix('admin')->controller(UserController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::middleware('auth:admin_api')->group(function () {
        Route::post('logout', 'logout');
        Route::group(['middleware'=>['api.admin']],function(){
            //menu
            Route::get('/getmenu',[MenuController::class, 'getmenu']);
            Route::get('/filteringmenu',[MenuController::class, 'get']);
            Route::get('/getdetailmenu/{id}',[MenuController::class, 'getdetail']);
            Route::post('/createmenu',[MenuController::class, 'createmenu']);
            Route::put('/updatemenu/{id}',[MenuController::class, 'updatemenu']);
            Route::delete('/deletemenu/{id}',[MenuController::class, 'deletemenu']);
            Route::post("/menuModel/uploadFoto/{id}", [MenuController::class, 'upload_foto']);

            //meja
            Route::get('/getmeja',[MejaController::class, 'getmeja']);
            Route::get('/getdetailmeja/{id}',[MejaController::class, 'getdetail']);
            Route::get('/filteringmeja',[MejaController::class, 'get']);
            Route::post('/createmeja',[MejaController::class, 'createmeja']);
            Route::put('/updatemeja/{id}',[MejaController::class, 'updatemeja']);
            Route::delete('/deletemeja/{id}',[MejaController::class, 'deletemeja']);

            //user
            Route::get('/getuser',[UserController::class, 'getuser']);
            Route::get('/getdetailuser/{id}',[UserController::class, 'getdetail']);
            Route::get('/filteringuser',[UserController::class, 'get']);
            Route::post('/createuser',[UserController::class, 'createuser']);
            Route::put('/updateuser/{id}',[UserController::class, 'updateuser']);
            Route::delete('/deleteuser/{id}',[UserController::class, 'deleteuser']);
        });
        Route::post('me', 'me');
    }); 
});

Route::prefix('kasir')->controller(UserController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::middleware('auth:kasir_api')->group(function () {
        Route::post('logout', 'logout');
        Route::group(['middleware'=>['api.kasir']],function(){
            //transaksi
            Route::post('/createtransaksi',[TransaksiController::class, 'createtransaksi']);
            Route::get('/gettransaksi',[TransaksiController::class, 'gettransaksi']);
            Route::put('/updatetransaksi/{id}',[TransaksiController::class, 'updatetransaksi']);
            Route::get('/gettransaksi/{id}',[TransaksiController::class, 'getdetail']);
            Route::get('/filteringtransaksi',[TransaksiController::class, 'get']);
            Route::delete('/deletetransaksi/{id}',[TransaksiController::class, 'deletetransaksi']);

            //detail transaksi
            Route::get('/get',[Detail_TransaksiController::class, 'get']);
            Route::get('/getdetail/{id}',[Detail_TransaksiController::class, 'getdetail']);
            Route::post('/additem',[Detail_TransaksiController::class, 'additem']);
        });
        Route::post('me', 'me');
    }); 
});

Route::prefix('manajer')->controller(UserController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
        Route::middleware('auth:manajer_api')->group(function () {
            Route::post('logout', 'logout');
            Route::group(['middleware'=>['api.manajer']],function(){
                Route::get('/get',[TransaksiController::class, 'gettransaksi']);
                Route::get('/get/{id}',[TransaksiController::class, 'getdetail']);
                Route::get('/filtering',[TransaksiController::class, 'get']);
        });
    Route::post('me', 'me');
    });
});
