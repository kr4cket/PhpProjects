<?php

namespace App\Http\Controllers;

use App\Http\Resources\GameResource;
use App\Http\Resources\StatusResourse;
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
        return $service->createNewGame();
    }

    public function status(Game $id, Player $code, GameService $service)
    {
        $info = $service->getInfo($code, $id);
        if ($info) {
            return response()->json($info);
        }

        return response()->json([
            'success'   => false,
            'error'     => 102,
            'message'   => "Ошибка получения статуса"
        ]);
    }

    public function ready(Game $id, Player $code, GameService $service)
    {
       $data = $service->getReady($id, $code);

        if($data['success']) {
            return response()->json($data);
        }

        return response()->json([
            'success'   => false,
            'error'     => 105,
            'message'   => "Ошибка подключения"
        ]);
    }

}
