<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Img;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{

    //ログインした後、ユーザ情報を返す処理
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. バリデーション
        $request->validate([
            'img_id' => 'nullable|exists:img,img_id',
            'user_mail' => 'required|unique:user|email|max:100',
            'user_name' => 'required|max:50',
            'password' => 'required|min:6',
            'life_id' => 'nullable|integer',
            'birth' => 'nullable|date',
            'blood_type' => 'nullable|max:10',
            'height' => 'nullable|integer',
            'hobby' => 'nullable|max:100',
            'episode1' => 'nullable|max:100',
            'episode2' => 'nullable|max:100',
            'episode3' => 'nullable|max:100',
            'episode4' => 'nullable|max:100',
            'episode5' => 'nullable|max:100',
            'abilities' => 'nullable',
        ]);

        // 2. ユーザーデータの保存
        $user = new User([
            'img_id' => $request->img_id,
            'user_mail' => $request->user_mail,
            'user_name' => $request->user_name,
            'password' => Hash::make($request->password),
            'life_id' => $request->life_id,
            'birth' => $request->birth,
            'blood_type' => $request->blood_type,
            'height' => $request->height,
            'hobby' => $request->hobby,
            'episode1' => $request->episode1,
            'episode2' => $request->episode2,
            'episode3' => $request->episode3,
            'episode4' => $request->episode4,
            'episode5' => $request->episode5,
            'abilities' => null, // トークンがまだ使用されていないので、nullを設定する
        ]);

        $user->save();

        // img_idに対応するimg_passを取得
        $img_path = null;
        if ($request->img_id) {
            $img = Img::find($request->img_id);
            if ($img) {
                // img_passはファイルのパスなので、それをasset関数に渡してURLを生成します
                $img_path = asset('storage/' . $img->img_pass);
            }
        }

        // 3. レスポンスの返却
        return response()->json([
            'message' => 'User created successfully',
            'user' => $user,
            'img_path' => $img_path
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::with('lives')->find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        // img_idに対応するimg_passを取得
        $img_path = null;
        if ($user->img_id) {
            $img = Img::find($user->img_id);
            if ($img) {
                // img_passはファイルのパスなので、それをasset関数に渡してURLを生成します
                $img_path = asset('storage/' . $img->img_pass);
            }
        }

        return response()->json(['user' => $user, 'img_path' => $img_path]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        // リクエストからIDを取得
        $id = $request->input('user_id');
        // 1. ユーザーが存在するか確認
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // 2. バリデーション
        $request->validate([
            'img_id' => 'nullable|exists:img,img_id',
            'user_mail' => 'required|email|max:100',
            'user_name' => 'required|max:50',
            'life_id' => 'nullable|integer',
            'birth' => 'nullable|date',
            'blood_type' => 'nullable|max:10',
            'height' => 'nullable|integer',
            'hobby' => 'nullable|max:100',
            'episode1' => 'nullable|max:100',
            'episode2' => 'nullable|max:100',
            'episode3' => 'nullable|max:100',
            'episode4' => 'nullable|max:100',
            'episode5' => 'nullable|max:100',
            'abilities' => 'nullable',
        ]);

        // 3. ユーザー情報のアップデート
        $user->fill([
            'img_id' => $request->img_id,
            'user_mail' => $request->user_mail,
            'user_name' => $request->user_name,
            'life_id' => $request->life_id,
            'birth' => $request->birth,
            'blood_type' => $request->blood_type,
            'height' => $request->height,
            'hobby' => $request->hobby,
            'episode1' => $request->episode1,
            'episode2' => $request->episode2,
            'episode3' => $request->episode3,
            'episode4' => $request->episode4,
            'episode5' => $request->episode5,
            'abilities' => $request->abilities,
        ]);

        $user->save();

        // img_idに対応するimg_passを取得
        $img_path = null;
        if ($request->img_id) {
            $img = Img::find($request->img_id);
            if ($img) {
                // img_passはファイルのパスなので、それをasset関数に渡してURLを生成します
                $img_path = asset('storage/' . $img->img_pass);
            }
        }

        // 4. レスポンスの返却
        return response()->json([
            'message' => 'User created successfully',
            'user' => $user,
            'img_path' => $img_path
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
