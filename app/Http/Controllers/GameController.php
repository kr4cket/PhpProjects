<?php

namespace App\Http\Controllers;

use App\Http\Resources\GameResource;
use App\Models\Game;
use App\Models\Player;
use App\Models\ShipInSea;
use App\Models\Shot;
use App\Services\GameService;
use Illuminate\Http\Request;

class GameController extends Controller
{
    public function start(GameService $service)
    {
        $response = $service->createNewGame();
        return response()->json($response);
    }

    public function status(Game $game, Player $player, GameService $service)
    {
        $info = $service->getInfo($player, $game);
        if ($info) {
            return response()->json($info);
        }

        return response()->json([
            'success'   => false,
            'error'     => 102,
            'message'   => "Ошибка получения статуса"
        ]);
    }

    public function ready(Game $game, Player $player, GameService $service)
    {

        if ($service->isShipsPlaced($player) && $game->status == Game::BEGIN) {

            $data = $service->getReady($game, $player);
            if ($data['success']) {
                return response()->json($data);
            }

        }

        return response()->json([
            'success'   => false,
            'error'     => 105,
            'message'   => "Ошибка!"
        ]);
    }

}
