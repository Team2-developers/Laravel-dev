<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FileUplodeController;

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
Route::post('/user', [UserController::class, 'store']);

//画像アップロード
Route::post('fileupload', [FileUplodeController::class, 'store']);
