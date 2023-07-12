<?php

namespace App\Http\Controllers;
use App\Services\GameService;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class GameController extends Controller
{
    private CLient $bot;
    private $botCode;
    private $gameId;

    public function startNewGame($url): void
    {
        $this->bot = new Client([
            'base_uri' => $url . '/api/',
            'timeout' => 2.0,
        ]);

        $response = $this->bot->request('POST', 'start/');

        $gameData = (array)json_decode($response->getBody());

        $this->botCode = $gameData['code'];
        $this->gameId = $gameData['id'];

    }

    public function connectToGame($params): void
    {
        $this->botCode = $params['playerCode'];
        $this->gameId = $params['gameId'];

    }

    public function startPlay($params, GameService $service)
    {
        $data = $service->generateShips();

        $this->bot = new Client([
            'base_uri' => $params['url'] . '/api/',
            'timeout' => 2.0,
        ]);

        $this->bot->request(
            'POST',
            'clear-field/'.$this->gameId.'/'.$this->botCode
        );

        $response = $this->bot->request(
            'POST',
            'place-ship/'.$this->gameId.'/'.$this->botCode,
            ['form_params' => ['ships' => $data]]
        );

//        print_r(json_decode($response->getBody()->getContents()));

        $response = $this->bot->request(
            'POST',
            'ready/'.$this->gameId.'/'.$this->botCode
        );

//        print_r(json_decode($response->getBody()->getContents()));
    }

}
