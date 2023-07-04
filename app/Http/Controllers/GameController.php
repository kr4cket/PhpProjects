<?php

namespace App\Http\Controllers;

use App\Http\Resources\GameResource;
use App\Models\Game;
use Illuminate\Http\Request;

class GameController extends Controller
{
    public function start(Game $model)
    {
        
        $game = $model->newGame();

        if ($game) {
            return response()->json(GameResource::make($game));
        } 

        return response()->json([
            'success'   => false,
            'error'     => 101,
            'message'   => "Не удалось создать игру"
        ]);
    }


    // public function index()
    // {

    // }

    // public function store(Request $request)
    // {

    // }

 
    // public function show(Game $game)
    // {
    // }

    // public function update(Request $request, Game $game)
    // {

    // }

    // public function destroy(Game $game)
    // {

    // }
}
