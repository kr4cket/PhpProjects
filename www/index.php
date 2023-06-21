<?php

spl_autoload_register( function ($className) {
    $path = str_replace('\\', DIRECTORY_SEPARATOR, ROOT.'/../src'.DIRECTORY_SEPARATOR.$className.'.php');
    require_once($path);
});

use App\Core\Router;

define('ROOT', realpath(__DIR__));
define('CONFIG_PATH', realpath(__DIR__).'/../configs/');
define('VIEW_PATH', realpath(__DIR__).'/../src/views/');
define('SRC_PATH', realpath(__DIR__).'/../src/');

require_once(ROOT.'/../src/App/Core/Router.php');
$routes = CONFIG_PATH.'routes.php';

$router = new Router($routes);
$router->start();

?>
