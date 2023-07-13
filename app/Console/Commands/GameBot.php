<?php

namespace App\Console\Commands;

use App\Api\ApiClient;
use App\Services\BotService;
use App\Services\GameService;
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
    public function handle(BotService $bot, GameService $service): void
    {
        $arguments = $this->arguments();
        $apiClient = new ApiClient($arguments['url'], $arguments['gameId'] ?? 0, $arguments['playerCode'] ?? '');
        $bot->setApiClient($apiClient);

        if (isset($arguments['gameId']) && isset($arguments['playerCode'])) {

            $message = "Подключение к игре...";
        } else {

            $url = $bot->startNewGame($arguments['url']);
            $message = "Игра создана".PHP_EOL."Ссылка для подключения к игре: $url".PHP_EOL."Ожидание игрока...";
        }

        $this->info($message);

        $gameResult = $bot->startGame($service);

        if ($gameResult) {
            $this->info("Поздравляю, вы победили!");
        } else {
            $this->error("Увы, вы проиграли");
        }
    }
}
