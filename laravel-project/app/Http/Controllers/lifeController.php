<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Life;
use App\Models\Trout;
use App\Models\User;
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

        return response()->json([
            'life' => $life,
            'torut' => $torutRecords,
        ]);
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

        return response()->json([
            'user' => $user,
            'lifes' => $lifes
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
}
