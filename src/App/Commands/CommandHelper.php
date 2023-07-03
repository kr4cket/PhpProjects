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

    public static function checkCommand($command): string
    {
        if (isset($command) && array_key_exists($command, self::$commands)) {
            return self::$commands[$command];
        }
        
        return self::$commands['default'];
    }

    public function execute()
    {
        $info = [];
        foreach (self::$commands as $key => $command) {
            if (strncmp($key, "--", 2)) {
                $info[] = $command::getInfo();
            }
        }
        return implode(PHP_EOL, $info);
    }

    public static function getInfo()
    {
        
    }
}