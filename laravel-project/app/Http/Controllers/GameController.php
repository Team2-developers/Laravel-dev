<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\Img;
use App\Models\User;
use App\Models\Life;
use App\Models\Trout;
use Exception;

class GameController extends Controller
{
    public function show(string $id)
    {
        try {
            // Gameテーブルからデータを取得する際に、関連するUserも一緒に取得
            $game = Game::with(['user1', 'user2', 'user3', 'user4'])->where('game_id', $id)->first();

            if ($game === null) {
                return response()->json(['message' => 'Game not found'], 404);
            }

            // 各ユーザーの画像パスを取得
            $game->user1->img_path = $this->getImagePathFromImgId($game->user1->img_id);
            if ($game->user2) $game->user2->img_path = $this->getImagePathFromImgId($game->user2->img_id);
            if ($game->user3) $game->user3->img_path = $this->getImagePathFromImgId($game->user3->img_id);
            if ($game->user4) $game->user4->img_path = $this->getImagePathFromImgId($game->user4->img_id);

            return response()->json($game, 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function joinGame(Request $request, string $id)
    {
        try {
            $game = Game::where('game_id', $id)->first();

            if ($game === null) {
                return response()->json(['message' => 'Game not found'], 404);
            }

            $user = $request->user();
            $userId = $user->user_id;

            if ($game->user_2 === null) {
                $game->user_2 = $userId;
            } elseif ($game->user_3 === null) {
                $game->user_3 = $userId;
            } elseif ($game->user_4 === null) {
                $game->user_4 = $userId;
            } else {
                return response()->json(['message' => 'No empty slots available'], 400);
            }

            $game->save();

            return response()->json($game, 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
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

    public function start(Request $request, $id)
    {
        // Find the game
        $game = Game::findOrFail($id);

        // Check if the game can be started
        if (strcasecmp(trim($game->game_status), 'notstarted') != 0) {
            return response()->json(['message' => 'Game already started'], 400);
        }

        // Start the game
        $game->game_status = 'started';
        $game->save();

        // Get the user ids
        $userIds = [$game->user_id, $game->user_2, $game->user_3, $game->user_4];

        // Find users for each user id, and use null if user not found
        $users = collect($userIds)->map(function ($userId) {
            $user = null;
            if ($userId) {
                $user = User::find($userId);
                if ($user) {
                    $user->img_path = $this->getImagePathFromImgId($user->img_id);
                }
            }
            return $user;
        });

        $lifeIds = $users->pluck('life_id')->filter()->values();
        $firstLifeId = $lifeIds->first();
        $life = Life::find($firstLifeId);

        $torutRecords = Trout::where('life_id', $life->life_id)->take(20)->get();

        return response()->json([
            'message' => 'Game started',
            'life' => $life,
            'torut' => $torutRecords,
            'users' => $users
        ]);
    }
}
