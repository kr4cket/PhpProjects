<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\Game;
use Illuminate\Http\Request;
use App\Services\MessageService;

class MessageController extends Controller
{

    public function get(MessageService $chat, Game $game, Player $player, Request $request)
    {
        $lastTime = $request->get('lastTime');
        $data = $chat->getMessages($game, $player, $lastTime);

        if ($data || $lastTime == 'false') {
            return response()->json([
                'messages' => $data['messages'] ?? '',
                'lastTime' => $data['lastTime'] ?? '',
                'success'  => true
            ]);
        }

        return response()->json([
            'success'   => false,
            'error'     => 107,
            'message'   => "Ошибка загрузки сообщений"
        ]);
    }

    public function send(MessageService $chat, Game $game, Player $player, Request $request)
    {
        $message = $request->post();
        $errors = $chat->sendMessage($player, $message['message']);

        if ($errors) {
            return response()->json([
                'success'   => true,
            ]);
        }
        return response()->json([
            'success'   => false,
            'error'     => 106,
            'message'   => "Ошибка отправки сообщения"
        ]);
    }

}
