<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FileUplodeController;
use App\Http\Controllers\lifeController;
use App\Http\Controllers\TroutController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\QRCodeController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\NotificationController;

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


//画像アップロード
Route::post('fileupload', [FileUplodeController::class, 'store']);

if (env('APP_AUTH_CHECK', true)) {
    // 認証を行う
    Route::middleware('auth:sanctum')->group(function () {

        //アカウント変更
        Route::post('/user/update', [UserController::class, 'update']);


        //人生作成
        Route::post('life/create', [lifeController::class, 'store']);
        //人生取得
        Route::get('/life/{life_id}', [LifeController::class, 'getLifeWithTorut']);
        //ユーザの人生を一覧で取得
        Route::get('/user/{user_id}/lifes', [LifeController::class, 'getUserinfo']);
        //人生とマスの作成
        Route::post('/createLifeAndTrout', [LifeController::class, 'storeLifeAndTrout']);
        //人生とマスの修正、変更
        Route::post('/updateLifeAndTrout', [LifeController::class, 'updateLifeAndTrout']);

        //マス作成
        Route::post('trout/create', [TroutController::class, 'store']);

        //Lifeテーブルのgoodカラムを増やす
        Route::post('/life/{life_id}/good', [LifeController::class, 'incrementGood']);

        //ゲームテーブル系作成
        Route::post('/games', [QRCodeController::class, 'store']);
        Route::get('/games/{game}', [QRCodeController::class, 'show'])->name('games.show');
        //ゲームテーブル最新情報を取得
        Route::get('/game/{id}', [GameController::class, 'show']);
        //ゲーム参加処理
        Route::middleware('auth:sanctum')->post('/game/join/{id}', [GameController::class, 'joinGame']);
        //ゲームスタート
        Route::get('/game/{id}/start', [GameController::class, 'start']);

        //コメント作成
        Route::post('/comments/create', [CommentController::class, 'store']);

        //通知取得
        Route::get('/notifications', [NotificationController::class, 'showNotifications']);


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

    //ゲームテーブル系作成
    Route::post('/games', [QRCodeController::class, 'store']);
    Route::get('/games/{game}', [QRCodeController::class, 'show'])->name('games.show');

    //コメント
    Route::apiResource('comments', CommentController::class);
}

//認証系
Route::post('/login', [AuthController::class, 'login']);
