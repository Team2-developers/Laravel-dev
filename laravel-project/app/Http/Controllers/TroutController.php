<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trout;

class TroutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
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
            'trouts' => 'required|array|size:20', // 20個のtroutデータが必要
            'trouts.*.trout_detail' => 'nullable|max:100',
            'trouts.*.life_id' => 'required|exists:life,life_id', // life_idはlifeテーブルのlife_idとして存在している必要があります
            'trouts.*.seqno' => 'required|integer',
            'trouts.*.point' => 'nullable|integer',
            'trouts.*.color' => 'required|max:50',
        ]);

        // 2. データの保存
        $createdTrouts = [];
        foreach ($request->trouts as $troutData) {
            $trout = new Trout([
                'trout_detail' => $troutData['trout_detail'],
                'life_id' => $troutData['life_id'],
                'seqno' => $troutData['seqno'],
                'point' => $troutData['point'] ?? 0,
                'color' => $troutData['color'],
            ]);
            $trout->save();
            $createdTrouts[] = $trout;
        }

        // 3. レスポンスの返却
        return response()->json(['message' => 'Trouts created successfully', 'trouts' => $createdTrouts], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
