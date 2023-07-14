<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;

class WebController
{

    const GAME_URL = "http://battleships.dev.sibirix.ru/";

    public function index(Game $game, Request $request)
    {
        $records = $game->getRecords(self::GAME_URL, $request->url());

        return view('games', ['records' => $records]);
    }

    public function statistics(Game $game)
    {
        if ($game::END == $game->status) {
            $stats = $game->getStats();

            return view('stats', ['stats' => $stats]);
        }

        abort(404);
    }
}
