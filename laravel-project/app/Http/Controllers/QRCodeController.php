<?php

namespace App\Http\Controllers;

use App\Models\Game;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Http\Request;

class QRCodeController extends Controller
{
    public function store(Request $request)
    {
        // Validate the request...

        $validatedData = $request->validate([
            'user_id' => 'required|integer',
        ]);

        $gameData = $request->all();
        $gameData['game_status'] = 'notstarted';
        $game = Game::create($gameData);

        $qrData = [
            'game_id' => $game->game_id,
            'game_status' => $game->game_status,
            'user_id' => $game->user_id
        ];

        $queryParams = http_build_query($qrData);

        // Generate QR Code
        return QrCode::size(300)->generate('http://localhost:8080/roomCreationComplete?' . $queryParams);
        // return QrCode::size(300)->generate(route('games.show', $game));
    }


    public function show(Game $game)
    {
        // Return the game's details as json for the QR scanner to read
        return $game;
    }
}
