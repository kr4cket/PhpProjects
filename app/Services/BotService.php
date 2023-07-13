<?php

namespace App\Services;

use App\Api\ApiClient;
use App\Services\GameService;

class BotService
{
    private ApiClient $apiClient;

    const BEGIN = 1;
    const IN_PROGRESS = 2;
    const END = 3;


    public function setApiClient(ApiClient $client): void
    {
        $this->apiClient = $client;
    }

    public function startNewGame($url): string
    {
        $this->apiClient->apiRequest('start');
        return $this->getInviteUrl($url);
    }

    public function getInviteUrl($url): string
    {
        $responseData = $this->apiClient->apiRequest('status');

        return $url.'/placement/'.$responseData->game->id.'/'.$responseData->game->invite;
    }

    public function startGame(GameService $service): bool
    {

        $responseData = $this->apiClient->apiRequest('status');

        $gameStatus = $responseData->game->status;
        $meReady = $responseData->game->meReady;

        if ($gameStatus == self::BEGIN && !$meReady) {
            $data = $service->generateShips();

            $this->apiClient->apiRequest('clear-field');
            $this->apiClient->apiRequest('place-ship',['ships' => $data]);

            echo("Корабли расставлены!".PHP_EOL);

            $this->apiClient->apiRequest('ready');

            echo("Я готов, жду противника!".PHP_EOL);
        }

        $result = $this->play($service);

        echo("Игра окончена!".PHP_EOL);

        return $result;
    }

    public function play(GameService $service): bool
    {
        $responseData = $this->apiClient->apiRequest('status');
        $status = $responseData->game->status;

        while ($status != self::END) {

            $responseData = $this->apiClient->apiRequest('status');

            $status = $responseData->game->status;
            $myTurn = $responseData->game->myTurn;
            $enemyField = $responseData->fieldEnemy;

            if ($status == self::IN_PROGRESS && $myTurn) {

                $data = $service->makeShot($enemyField);
                $isShot = $this->apiClient->apiRequest('shot', $data);

                echo("Выстрел сделан! Координаты выстрела".PHP_EOL."X: ".$data['x']." Y: ".$data['y'].PHP_EOL);

                if (isset($isShot->hit) && $isShot->hit) {
                    echo("Попадание!".PHP_EOL);
                }

                if (isset($isShot->kill) && $isShot->kill) {
                    echo("Корабль противника потоплен!!".PHP_EOL);
                }
            }

            sleep(1);
        }

        return !empty($myTurn);

    }

}
