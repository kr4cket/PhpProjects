<?php

namespace App\Core;

abstract Class ConsoleCommand
{
    public static $commands = [
        "--create_recordings" => \App\Commands\CreateRecordings::class,
        "-cr" => \App\Commands\CreateRecordings::class,
        "--get_statistics" => \App\Commands\GetStatistics::class,
        "-gs" => \App\Commands\GetStatistics::class,
        "--change_good_description" => \App\Commands\ChangeGoodDescription::class,
        "-cgd" => \App\Commands\ChangeGoodDescription::class
    ];

    public static function getCommands()
    {
        return "-cr, --create_recordings [OPTION] [RECORDS_NUM] - Заполняет базу данных".
            PHP_EOL."OPTIONS".PHP_EOL."-g - Наполнить таблицу товаров".
            PHP_EOL."-r - Наполнить таблицу комментариев".
            PHP_EOL."-gs, --get_statistics [OPTION] - Получить информацию о сайте".
            PHP_EOL."OPTIONS".PHP_EOL."-a  - Получить всю информацию".
            PHP_EOL."-ag - Получить количество не распроданных товаров".
            PHP_EOL."-ar - Получить количество активных отзывов".
            PHP_EOL."-g  - Получить количество всех товаров".
            PHP_EOL."-mr - Получить количество отзывов на модерации".
            PHP_EOL."-r  - Получить количество всех отзывов".
            PHP_EOL."-sg - Получить количество распроданных товаров";
    }

    public abstract function execute($args);
}