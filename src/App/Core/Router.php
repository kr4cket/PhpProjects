<?php
    namespace App\Core;

    class Router
    {
        private $routes;

        public function __construct($routesPath)
        {
            $this->routes = include($routesPath);
        }

        public function start()
        {
            $uri = parse_url($_SERVER['REQUEST_URI']);
            $uriPath = $uri['path'];
            if (array_key_exists('query', $uri)) {
                parse_str($uri['query'], $uriParams);
            } else {
                $uriParams = [];
            }
            foreach ($this->routes as $pattern => $route) {
                if ($pattern == $uriPath) {
                    $controller = new $route[0]();
                    $action = $route[1];
                } else if (preg_match("~^$pattern\$~", $uriPath)) {
                    $uriParams = array_slice(explode('/', $uriPath),2);
                    $controller = new $route[0]();
                    $action = $route[1];
                }
                
                if (isset($controller)) {
                    $page = $controller->$action(...$uriParams);
                    $page->render();
                    return;
                }
            }
            $controller = new \App\Controllers\NotFoundController();
            $page = $controller->index($uri);
            $page->render();
        }
    }

