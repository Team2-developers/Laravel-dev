<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Img;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Database\QueryException;

class UserController extends Controller
{

    private function createUser(Request $request)
    {
        $user = new User($request->validated());

        $user->password = Hash::make($request->password);
        $user->abilities = null;

        $user->save();

        $user->token = $user->createToken('my-app-token')->plainTextToken;

        return $user;
    }

    private function getImagePathFromImgId($img_id)
    {
        $img_path = null;
        if ($img_id) {
            $img = Img::find($img_id);
            if ($img) {
                $img_path = asset('storage/' . $img->img_pass);
            }
        }
        return $img_path;
    }

    public function me()
    {
        return response()->json(auth()->user());
    }

    public function index()
    {
        //
    }

    public function store(StoreUserRequest $request)
    {
        try {
            $user = $this->createUser($request);
            $img_path = $this->getImagePathFromImgId($request->img_id);

            return response()->json([
                'message' => 'User created successfully',
                'user' => $user,
                'img_path' => $img_path
            ], 201);
        } catch (QueryException $e) {
            return $this->handleQueryException($e);
        }
    }


    public function show(string $id)
    {
        try {
            $user = User::with('lives')->find($id);

            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }
            $img_path = $this->getImagePathFromImgId($user->img_id);

            return response()->json(['user' => $user, 'img_path' => $img_path]);
        } catch (QueryException $e) {
            return $this->handleQueryException($e);
        }
    }

    public function update(StoreUserRequest $request, string $id)
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }

            $user->fill($request->validated());

            $user->save();

            $img_path = $this->getImagePathFromImgId($request->img_id);

            return response()->json([
                'message' => 'User updated successfully',
                'user' => $user,
                'img_path' => $img_path
            ], 200);
        } catch (QueryException $e) {
            return $this->handleQueryException($e);
        }
    }

    public function destroy(string $id)
    {
        //
    }

    // エラーハンドリング
    private function handleQueryException(QueryException $e)
    {
        if ($e->getCode() == 23000) {
            return response()->json([
                'error' => '入力項目の重複',
                'message' => '入力したメールアドレスは既に使用されています。',
                'sys_error' => $e
            ], 409);
        }

        return response()->json([
            'error' => 'データベースエラー',
            'message' => 'リクエストの処理中にエラーが発生しました。後ほど再度お試しください。',
            'sys_error' => $e
        ], 500);
    }
}
