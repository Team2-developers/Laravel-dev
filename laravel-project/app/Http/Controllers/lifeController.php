<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Life;

class lifeController extends Controller
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
