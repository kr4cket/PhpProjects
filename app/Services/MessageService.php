<?php

namespace App\Services;

use App\Models\Game;
use App\Models\Player;

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

        $myMessages = $player->messages;
        $enemyMessages = $enemy->messages;

        $messages = $myMessages->merge($enemyMessages);

        foreach ($messages as $message) {
            $response = [];
            $response['time'] = strtotime($message->time);
            if ($response['time'] > $data['lastTime']) {

                $response['my'] = $player->id == $message->player_id ? true : false;
                $response['message'] = $message->message;
                $data['messages'][] = $response;
                $data['lastTime'] = $response['time']+1;
            }

        }

        return $data;
    }
}
