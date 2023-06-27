<?php

namespace App\Commands;
use App\Core\ConsoleCommand;

class CommandHelper extends ConsoleCommand
{
    private static $commands = [
        "--create_goods" => \App\Commands\CreateGoods::class,
        "-cg" => \App\Commands\CreateGoods::class,
        "--create_reviews" => \App\Commands\CreateReviews::class,
        "-cr" => \App\Commands\CreateReviews::class,
        "--get_statistics" => \App\Commands\GetStatistics::class,
        "-gs" => \App\Commands\GetStatistics::class,
        "--change_good_description" => \App\Commands\ChangeGoodDescription::class,
        "-cgd" => \App\Commands\ChangeGoodDescription::class,
        "default" =>\App\Commands\CommandHelper::class
    ];

    public static function checkCommand($command)
    {
        if (isset($command) && array_key_exists($command, self::$commands)) {
            return self::$commands[$command];
        }
        return self::$commands['default'];
    }

    public function execute()
    {
        return 
        "-cgd, --create_good_description [ID] [TEXT] - Изменяет описание конкретного товара".PHP_EOL.
        "-cg, --create_goods [GOODS_NUM] - Заполняет базу данных товаров".PHP_EOL.
        "-cr, --create_reviews [REVIEWS_NUM] - Заполняет базу данных отзывов".PHP_EOL.
        "-gs, --get_statistics [OPTION] - Получить информацию о сайте";
    }

    public function getInfo()
    {
        
    }
}