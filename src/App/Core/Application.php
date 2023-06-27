<?php

namespace App\Core;
use App\Core\Router;
use Exception;

class Application
{
    private $router;

    public function __construct()
    {
        define('ROOT', realpath(__DIR__));
        define('CONFIG_PATH', realpath(__DIR__).'/../../../configs/');
        define('VIEW_PATH', realpath(__DIR__).'/../../views/');
        define('SRC_PATH', realpath(__DIR__).'/../../../');
        $this->router = new Router(CONFIG_PATH.'routes.php');    
    }
    public function run()
    {
        try {
            $this->router->start();
        } catch (Exception $error) {
            echo $error->getMessage();
        }
    }

    public function consoleRun($argv) 
    {
        if (array_key_exists($argv[1], ConsoleCommand::$commands)) {
            $command = new ConsoleCommand::$commands[$argv[1]]();
            echo($command->execute(array_slice($argv, 2)));
        }else {
            echo(ConsoleCommand::getCommands());
        }
    }
}