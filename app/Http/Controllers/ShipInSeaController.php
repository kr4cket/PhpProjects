<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShipInSea;
use App\Models\Game;
use App\Models\Player;
use App\Models\Shot;
use App\Services\GameService;

class ShipInSeaController extends Controller
{
    public function place(Game $game, Request $request, Player $player, GameService $service)
    {
        $error = 'Вы уже объявили о своей готовности!';

        if ($game->status == Game::BEGIN && $player->me_ready == 0) {

            $postData = $request->post();
            $error = $service->placeShips($postData, $player);

            if (empty($error)) {

                return response()->json([
                    'success'   => true,
                ]);
            }

        }


        return response()->json([
            'success'   => false,
            'error'     => 109,
            'message'   => $error
        ]);
    }

    public function shot(Game $game, Player $player, GameService $service, Request $request)
    {
        $message = "Невозможно выстрелить!";

        if ($game->status == $game::IN_PROCESS) {
            $data = $request->post();
            $response = $service->makeShot($game, $player, $data);

            if($response) {
                return response()->json([
                    'success' => $response,
                ]);
            }

            $message = "сюда уже стреляли";

        }

        return response()->json([
            'success'   => false,
            'error'     => 105,
            'message'   => $message
        ]);
    }

    public function clear(Game $game, Player $player, GameService $service)
    {
        if ($player->me_ready == 0) {
            return response()->json([
                'success'   => $service->clearField($player) ?? false,
            ]);
        }
    }
}
