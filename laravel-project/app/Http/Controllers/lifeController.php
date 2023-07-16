<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Life;
use App\Models\Trout;
use App\Models\User;
use App\Models\Img;
use App\Models\Comment;
use Illuminate\Support\Facades\DB;


class lifeController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. バリデーション
        $request->validate([
            'life_name' => 'required|max:50',
            'life_detail' => 'required|max:100',
            'message' => 'required|max:50',
            'user_id' => 'required|exists:user,user_id',
        ]);

        // 2. データの保存
        $life = new Life([
            'life_name' => $request->life_name,
            'life_detail' => $request->life_detail,
            'message' => $request->message,
            'user_id' => $request->user_id,
        ]);

        $life->save();

        // 3. レスポンスの返却
        return response()->json(['message' => 'Life created successfully', 'life' => $life], 201);
    }


    public function getLifeWithTorut($life_id)
    {
        $life = Life::find($life_id);

        if (!$life) {
            return response()->json(['message' => 'Life not found'], 404);
        }

        $torutRecords = Trout::where('life_id', $life_id)->take(20)->get();
        $img_path = $this->getImagePathFromImgId($life->img_id);

        return response()->json([
            'life' => $life,
            'torut' => $torutRecords,
            'img_path' => $img_path
        ]);
    }

    public function storeLifeAndTrout(Request $request)
    {
        $request->validate([
            'life_name' => ['required', 'max:50'],
            'life_detail' => ['required', 'max:100'],
            'message' => ['required', 'max:50'],
            'user_id' => ['required', 'exists:user,user_id'],
            'img_id' => ['nullable', 'exists:img,img_id'],
            'trouts' => ['required', 'array'],
            'trouts.*.trout_detail' => ['nullable', 'max:100'],
            'trouts.*.seqno' => ['required', 'integer'],
            'trouts.*.point' => ['nullable', 'integer'],
            'trouts.*.color' => ['required', 'max:50'],
        ]);

        $life = null;

        DB::transaction(function () use ($request, &$life) {
            $life = new Life([
                'life_name' => $request->life_name,
                'life_detail' => $request->life_detail,
                'message' => $request->message,
                'user_id' => $request->user_id,
                'good' => 0, // 初期値として0を設定
            ]);
            if (isset($request->img_id) && !is_null($request->img_id)) {
                $life->img_id = $request->img_id;
            }

            $life->save();

            foreach ($request->trouts as $troutData) {
                $trout = new Trout($troutData + ['life_id' => $life->life_id]);

                $trout->save();
            }
        });

        if ($life) {
            return response()->json([
                'message' => 'Life and trouts created successfully',
                'life' => $life,
                'trouts' => Trout::where('life_id', $life->life_id)->get()
            ]);
        } else {
            return response()->json([
                'message' => 'An error occurred while creating life and trouts'
            ], 500);
        }
    }





    public function updateLifeAndTrout(Request $request)
    {
        DB::transaction(function () use ($request) {
            // lifeテーブルの更新
            $life = Life::find($request->life_id);
            $life->updateLife($request->all());

            //seqnoに対応するカラム値を更新
            foreach ($request->trouts as $troutData) {
                $trout = Trout::where('life_id', $request->life_id)
                    ->where('seqno', $troutData['seqno'])
                    ->first();

                // Add null check here
                if ($trout !== null) {
                    $trout->updateTrout($troutData);
                }
            }
        });

        return response()->json([
            'message' => 'update successfully',
            'life' => Life::find($request->life_id),
            'trouts' => Trout::where('life_id', $request->life_id)->get()
        ]);
    }


    public function getUserinfo($user_id)
    {
        $user = User::find($user_id);
        $lifes = Life::where('user_id', $user_id)->get();

        if (!$user || $lifes->isEmpty()) {
            return response()->json(['message' => 'User or lifes not found for this user'], 404);
        }

        $lifesWithComments = $lifes->map(function ($life) {
            $lifeComments = Comment::where('life_id', $life->life_id)->orderBy('comment_id', 'desc')->limit(20)->get();
            $comments = [];

            foreach ($lifeComments as $comment) {
                $commentUser = User::find($comment->user_id);
                $commentInfo = [
                    'user_id' => $commentUser->user_id,
                    'user_name' => $commentUser->user_name,
                    'user_email' => $commentUser->user_mail,
                    'img_path' => $this->getImagePathFromImgId($commentUser->img_id),
                    'comment' => $comment->comment
                ];
                array_push($comments, $commentInfo);
            }

            $life->comments = $comments;
            return $life;
        });

        return response()->json([
            'user' => $user,
            'lifes' => $lifesWithComments
        ]);
    }


    public function incrementGood(Request $request, $life_id)
    {
        $life = Life::find($life_id);

        if (!$life) {
            return response()->json(['message' => 'Life not found'], 404);
        }

        // 増分のバリデーション
        $request->validate([
            'increment' => 'required|integer|min:1'
        ]);

        // 増分を取得して good を増やす
        $increment = $request->input('increment');
        $life->increment('good', $increment);

        return response()->json([
            'message' => 'Good incremented successfully',
            'life' => $life
        ]);
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
}
