<?php

namespace App\Http\Middleware;

use App\Models\Game;
use App\Models\Player;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckGameAndPlayer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    public function handle(Request $request, Closure $next): Response
    {

        $game = $request->route('game');
        $player = $request->route('player');

        if (empty($game) && empty($player) ) {
            return $next($request);

        } elseif ($game->id == $player->game_id) {
            return $next($request);
        }

        return response()->json([
            'success'   => false,
            'error'     => 100,
            'message'   => "Игра не найдена"
        ]);
    }
}
