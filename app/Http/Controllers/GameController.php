<?php

namespace App\Http\Controllers;

use App\Http\Resources\GameResource;
use App\Http\Resources\StatusResourse;
use App\Models\Game;
use App\Models\Player;
use App\Models\ShipInSea;
use App\Models\Shot;
use Illuminate\Http\Request;

class GameController extends Controller
{
    public function start(Game $model, Player $player)
    {
        
        $game = $model->newGame();

        if ($game) {
            $player->addPlayers($game['code'], $game['invite']);
            return response()->json(GameResource::make($game));
        } 

        return response()->json([
            'success'   => false,
            'error'     => 101,
            'message'   => "Не удалось создать игру"
        ]);
    }

    public function status($id, $code, Game $model, ShipInSea $field, Shot $shots, Player $player)
    {
        $info = $model->getGameInfo($id, $code, $field, $shots, $player);
        if ($info) {
            return response()->json($info);
        }

        return response()->json([
            'success'   => false,
            'error'     => 102,
            'message'   => "Ошибка получения статуса"
        ]);
    }

    public function ready($id, $code, Game $model, Player $player)
    {
        $data = $model->getReady($id, $code, $player);

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
