<?php

namespace App\Http\Controllers;
use App\Services\GameService;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class GameController extends Controller
{
    private CLient $bot;
    private string $botCode;
    private string $gameId;

    const BEGIN = 1;
    const END = 3;


    private function connectToServer($url): void
    {
        $this->bot = new Client([
            'base_uri' => $url . '/api/',
            'timeout' => 2.0,
        ]);
    }

    private function apiRequest(string $apiMethod, string $gameId = '', string $botCode = '', $data = []): mixed
    {
        $apiMethod.='/';

        if (empty($gameId) && empty($playerCode) && empty($data)) {
            return json_decode($this->bot->request('POST', $apiMethod)->getBody()->getContents());
        }

        if (empty($data)) {
            return json_decode($this->bot->request('POST', $apiMethod.$gameId.'/'.$botCode)->getBody()->getContents());
        }

        return json_decode($this->bot->request('POST', $apiMethod.$gameId.'/'.$botCode, ['form_params' => $data])->getBody()->getContents());
    }

    public function startNewGame($url): string
    {
        $this->connectToServer($url);
        $gameData = $this->apiRequest('start');
        $this->botCode = $gameData->code;
        $this->gameId = $gameData->id;

        return $this->getInviteUrl($url);
    }

    public function getInviteUrl($url): string
    {
        $responseData = $this->apiRequest('status', $this->gameId, $this->botCode);

        return $url.'/placement/'.$this->gameId.'/'.$responseData->game->invite;
    }

    public function connectToGame($params): void
    {
        $this->connectToServer($params['url']);
        $this->botCode = $params['playerCode'];
        $this->gameId = $params['gameId'];
    }

    public function startGame(GameService $service): bool
    {

        $responseData = $this->apiRequest('status', $this->gameId, $this->botCode);

        $gameStatus = $responseData->game->status;

        if ($gameStatus == self::BEGIN) {
            $data = $service->generateShips();

            $this->apiRequest('clear-field', $this->gameId, $this->botCode);
            $this->apiRequest('place-ship', $this->gameId, $this->botCode, ['ships' => $data]);

            echo("Корабли расставлены!".PHP_EOL);

            $this->apiRequest('ready', $this->gameId, $this->botCode);

            echo("Я готов, жду противника!".PHP_EOL);
        }

        $result = $this->play($service);

        echo("Игра окончена!".PHP_EOL);

        return $result;
    }

    public function play(GameService $service): bool
    {
        $status = 1;
        $myTurn = 0;

        while ($status != self::END) {

            $responseData = $this->apiRequest('status', $this->gameId, $this->botCode);

            $status = $responseData->game->status;
            $myTurn = $responseData->game->myTurn;
            $enemyField = $responseData->fieldEnemy;

            if ($status == 2 && $myTurn == 1) {
                $data = $service->makeShot($enemyField);
                $isShot = $this->apiRequest('shot', $this->gameId, $this->botCode, $data);

                echo("Выстрел сделан! Координаты выстрела".PHP_EOL."X: ".$data['x']." Y: ".$data['y'].PHP_EOL);

                if ($isShot->hit) {
                    echo("Попадание!".PHP_EOL);
                }

                if (isset($isShot->kill)) {
                    echo("Корабль противника потоплен!!".PHP_EOL);
                }
            }

            sleep(1);
        }

        return !empty($myTurn);

    }

}
