<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Img;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Authenticate the user and return a token.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        // Validate request parameters.
        $request->validate([
            'user_mail' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Attempt to get the user from the database.
        $user = User::where('user_mail', $request->user_mail)->first();

        // If the user is not found or the password is incorrect, throw a validation exception.
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'The provided credentials are incorrect.'
            ], 401);
        }

        // Create a new token for the user.
        $token = $user->createToken('my-app-token')->plainTextToken;

        // img_idに対応するimg_passを取得
        $img_path = null;
        if ($user->img_id) {
            $img = Img::find($user->img_id);
            if ($img) {
                // img_passはファイルのパスなので、それをasset関数に渡してURLを生成します
                $img_path = asset('storage/' . $img->img_pass);
            }
        }

        // Return the user and token as JSON.
        return response()->json(['user' => $user, 'token' => $token, 'img_path' => $img_path], 200);
    }

    /**
     * Return the authenticated user.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function user(Request $request)
    {
        $user = $request->user();

        // img_idに対応するimg_passを取得
        $img_path = null;
        if ($user->img_id) {
            $img = Img::find($user->img_id);
            if ($img) {
                // img_passはファイルのパスなので、それをasset関数に渡してURLを生成します
                $img_path = asset('storage/' . $img->img_pass);
            }
        }

        // ユーザー情報と画像ファイルのURLを含むレスポンスを返します
        return response()->json(['user' => $user, 'img_path' => $img_path]);
    }
}
