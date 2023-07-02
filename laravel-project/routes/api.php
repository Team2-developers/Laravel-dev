<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FileUplodeController;
use App\Http\Controllers\lifeController;
use App\Http\Controllers\TroutController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//ログイン後の処理
Route::middleware('auth:api')->get('/user', [UserController::class, 'me']);

//アカウント作成
Route::post('/user/create', [UserController::class, 'store']);

//アカウント変更
Route::post('/user/update', [UserController::class, 'update']);

//画像アップロード
Route::post('fileupload', [FileUplodeController::class, 'store']);

if (env('APP_AUTH_CHECK', true)) {
    // 認証を行う
    Route::middleware('auth:sanctum')->group(function () {
        //人生作成
        Route::post('life/create', [lifeController::class, 'store']);
        //人生取得
        Route::get('/life/{life_id}', [LifeController::class, 'getLifeWithTorut']);
        //ユーザの人生を一覧で取得
        Route::get('/user/{user_id}/lifes', [LifeController::class, 'getUserinfo']);

        //マス作成
        Route::post('trout/create', [TroutController::class, 'store']);

        //Lifeテーブルのgoodカラムを増やす
        Route::post('/life/{life_id}/good', [LifeController::class, 'incrementGood']);

        //ユーザ情報取得
        Route::get('/user', [AuthController::class, 'user']);
    });
} else {
    //人生作成
    Route::post('life/create', [lifeController::class, 'store']);
    //人生取得
    Route::get('/life/{life_id}', [LifeController::class, 'getLifeWithTorut']);
    //ユーザの人生を一覧で取得
    Route::get('/user/{user_id}/lifes', [LifeController::class, 'getUserinfo']);

    //マス作成
    Route::post('trout/create', [TroutController::class, 'store']);

    //Lifeテーブルのgoodカラムを増やす
    Route::post('/life/{life_id}/good', [LifeController::class, 'incrementGood']);
}

//認証系
Route::post('/login', [AuthController::class, 'login']);
