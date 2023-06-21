<?php

    namespace App\Core;

    spl_autoload_register( function ($className) {
        $path = str_replace('\\', DIRECTORY_SEPARATOR, ROOT.'/../src'.DIRECTORY_SEPARATOR.$className.'.php');
        require_once($path);
    });

    class Router 
    {
        private $routes;

        public function __construct($routesPath)
        {
            $this->routes = include($routesPath);
        }

        private function getURI()
        {
            return $_SERVER['REQUEST_URI'];
        }

        public function start()
        {
            $uri = $this->getURI();
            foreach ($this->routes as $pattern => $route) {
                $matches = [];
                if (preg_match("~^$pattern\$~", $uri, $matches)) {
                    $routeParams = array_slice($matches, 1);
                    if (class_exists($route[0]) && method_exists($route[0], $route[1])) {
                        $controller = new $route[0]();
                        $action = $route[1];
                        $controller->$action($routeParams);
                        return;
                        
                    } else {
                        $controller = new \App\Controllers\NotFoundController();
                        $controller->index($uri);
                        return;
                    }
                }

            }
            $controller = new \App\Controllers\NotFoundController();
            $controller->index($uri);
            return;
        }
    }
?>