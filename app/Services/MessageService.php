<?php

namespace App\Services;

use App\Models\Game;
use App\Models\Message;
use App\Models\Player;
use DateTime;

class MessageService
{
    public function sendMessage(Player $player, string $message)
    {
        $message = $player->messages()->create([
            'player_id' => $player->id,
            'message'   => mb_strimwidth($message, 0, 250)
        ]);

        if ($message) {
            return true;
        }
        return false;
    }


    public function getMessages(Game $game, Player $player, $lastTime)
    {

        if ($lastTime != 'false') {
            $data['lastTime'] = $lastTime;
        } else {
            $data['lastTime'] = 0;
        }

        $enemy = $game->getEnemy($player);

        $messages = Message::where('time', '>', date('Y/m/d H:i:s', $data['lastTime']))
            ->where(function ($query) use ($enemy, $player){
                $query->where('player_id', '=', $player->id)
                    ->orWhere('player_id', '=', $enemy->id);
            })
            ->orderBy('time')
            ->get();


        foreach ($messages as $message) {
            $response = [];

            $response['time'] = strtotime($message->time);
            $response['my'] = $player->id == $message->player_id ? true : false;
            $response['message'] = $message->message;

            $data['messages'][] = $response;
            $data['lastTime'] = $response['time'];

        }

        return $data;
    }
}
