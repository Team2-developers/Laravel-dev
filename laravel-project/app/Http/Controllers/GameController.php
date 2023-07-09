<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use Exception;

class GameController extends Controller
{
    public function show(string $id)
    {
        try {
            $game = Game::where('game_id', $id) ->first();

            if ($game === null) {
                return response()->json(['message' => 'Game not found'], 404);
            }

            return response()->json($game, 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
