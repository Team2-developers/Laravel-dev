<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trout;

class TroutController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. バリデーション
        $request->validate($this->getValidationRules());

        // life_idを取得
        $life_id = $request->life_id;

        // 2. データの保存
        $createdTrouts = array_map(function ($troutData) use ($life_id) {
            return $this->createTrout($troutData, $life_id);
        }, $request->trouts);

        // 3. レスポンスの返却
        return response()->json([
            'message' => 'Trouts created successfully',
            'trouts' => $createdTrouts
        ], 201);
    }

    private function getValidationRules(): array
    {
        return [
            'life_id' => 'required|exists:life,life_id', // life_idはlifeテーブルのlife_idとして存在している必要があります
            'trouts' => 'required|array|size:20', // 20個のtroutデータが必要
            'trouts.*.trout_detail' => 'nullable|max:100',
            'trouts.*.seqno' => 'required|integer',
            'trouts.*.point' => 'nullable|integer',
            'trouts.*.color' => 'required|max:50',
        ];
    }

    private function createTrout(array $troutData, $life_id): Trout
    {
        $trout = new Trout([
            'trout_detail' => $troutData['trout_detail'],
            'life_id' => $life_id,
            'seqno' => $troutData['seqno'],
            'point' => $troutData['point'] ?? 0,
            'color' => $troutData['color'],
        ]);

        $trout->save();

        return $trout;
    }
}
