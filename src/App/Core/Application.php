<?php

namespace App\Core;

use App\Core\Router;
use App\Commands\CommandHelper;
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
        define("STYLES", realpath(__DIR__).'/../../styles');
        
        session_start();
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
        $command = CommandHelper::checkCommand($argv[1] ?? 'default');
        $command = new $command();
        echo($command->execute(...array_slice($argv, 2)));
    }
}