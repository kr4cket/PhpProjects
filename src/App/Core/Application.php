<?php

namespace App\Core;
use App\Core\Router;
use Exception;

define('ROOT', realpath(__DIR__));
define('CONFIG_PATH', realpath(__DIR__).'/../../../configs/');
define('VIEW_PATH', realpath(__DIR__).'/../../views/');
define('SRC_PATH', realpath(__DIR__).'/../../../');
require_once(ROOT.'/Router.php');


class Application
{
    private $router;

    public function __construct()
    {
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
}