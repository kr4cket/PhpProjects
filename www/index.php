<?php

    use App\Core\Router;

    define('ROOT', realpath(__DIR__));

    require_once(ROOT.'/../src/App/Core/Router.php');
    $routes = ROOT.'/../configs/routes.php';

    $router = new Router($routes);
    $router->start();


?>
