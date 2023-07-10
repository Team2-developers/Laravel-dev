<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index()
    {
        return Comment::all();
    }

    public function store(Request $request)
    {
        return Comment::create($request->all());
    }

    public function show(Comment $comment)
    {
        return $comment;
    }

    public function update(Request $request, Comment $comment)
    {
        $comment->update($request->all());

        return $comment;
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();
        return response()->json([], 204);
    }
}
