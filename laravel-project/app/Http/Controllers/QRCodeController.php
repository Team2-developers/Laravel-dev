<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\User;
use App\Models\Img;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Http\Request;

class QRCodeController extends Controller
{
    public function store(Request $request)
    {
        $game = $this->createGameFromRequest($request);

        $user = User::find($game->user_id);
        $img_path = $this->getImagePath($user);

        $qrData = [
            'game_id' => $game->game_id,
            'game_status' => $game->game_status,
            'user_name' => $user->user_name,
            'user_mail' => $user->user_mail,
            'img_path' => $img_path
        ];

        $svgWithoutDeclaration = $this->generateQrCode($qrData);

        // Create an array that includes both the SVG and the game_id
        $response = [
            'svg' => $svgWithoutDeclaration,
            'game_id' => $game->game_id,
        ];

        // Return the response as a JSON
        return response()->json($response);
    }

    private function createGameFromRequest(Request $request): Game
    {
        $gameData = $request->all();
        $gameData['game_status'] = 'notstarted'; // explicitly setting the value
        return Game::create($gameData);
    }

    private function getImagePath(?User $user): ?string
    {
        if ($user && $user->img_id) {
            $img = Img::find($user->img_id);
            if ($img) {
                return asset('storage/' . $img->img_pass);
            }
        }
        return null;
    }

    private function generateQrCode(array $qrData): string
    {
        $queryParams = http_build_query($qrData);

        $svg = QrCode::format('svg')->size(300)->generate('http://localhost:8080/RoomJoin?' . $queryParams);

        // Remove XML Declaration
        $dom = new \DOMDocument;
        $dom->loadXML($svg);
        return $dom->saveXML($dom->documentElement);
    }



    public function show(Game $game)
    {
        // Return the game's details as json for the QR scanner to read
        return $game;
    }
}
