<?php
    namespace Console;

    require_once ('vendor/autoload.php');
    
    use App\Core\Application;

    $app = new Application();
    $app->consoleRun($argv);

