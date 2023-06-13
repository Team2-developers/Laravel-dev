<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use App\Models\Img;


class FileUplodeController extends Controller
{
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
        if (!$request->hasFile('file')) {
            return response()->json(['message' => 'No file uploaded'], 400);
        }

        $file_name = $request->file('file')->getClientOriginalName();
        $file_path = $request->file('file')->storeAs('public', $file_name);

        // Create a new record in the Img table
        $img = new Img;
        $img->img_pass = $file_path;
        $img->save();

        return response()->json([
            'message' => 'File uploaded and record created successfully',
            'img_id' => $img->id,
            'img_path' => asset('storage/'.$file_name)
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
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
