<?php

namespace App\Console\Commands;

use App\Http\Controllers\ApiPerformanceController;
use App\Http\Controllers\GameController;
use App\Services\GameService;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class GameBot extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot {url} {gameId?} {playerCode?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(GameController $game, GameService $service)
    {
        $arguments = $this->arguments();

        if (isset($arguments['gameId']) && isset($arguments['playerCode'])) {

            $game->connectToGame($arguments);
            $message = "Подключение к игре...";
        } else {

            $game->startNewGame($arguments['url']);
            $message = "Игра создана\nОжидание игрока...";
        }

        $game->startPlay($arguments, $service);

        $this->info($message);
    }
}
