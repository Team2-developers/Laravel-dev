<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Life;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function showNotifications(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Fetch all Life objects related to the logged in user
        $lives = Life::where('user_id', $user->user_id)->get();

        // Fetch all Comment objects related to the Life objects
        $comments = array();
        foreach ($lives as $life) {
            $lifeComments = Comment::where('life_id', $life->life_id)->orderBy('comment_id', 'desc')->limit(20)->get();
            foreach ($lifeComments as $comment) {
                $commentUser = User::find($comment->user_id);
                $commentInfo = [
                    'user_id' => $commentUser->user_id,
                    'user_name' => $commentUser->user_name,
                    'user_email' => $commentUser->user_mail,
                    'img_id' => $commentUser->img_id,
                    'message' => $commentUser->user_name . 'さんがコメントしました'
                ];
                array_push($comments, $commentInfo);
            }
        }

        return response()->json(['notification' => $comments], 200);
    }
}
