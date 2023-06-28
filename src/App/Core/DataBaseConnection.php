<?php

namespace App\Core;

use Exception;
use \PDO;

class DataBaseConnection
{
    private static $instance;
    private $connection;

    private function __construct()
    {
        $this->dbConnect();
    }

    public static function getInstance(): object
    {
        if (self::$instance === null) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    private function dbConnect()
    {
        if (file_exists(CONFIG_PATH.'db_connect.ini')) {
            $connectionArgs = parse_ini_file(CONFIG_PATH.'db_connect.ini');
            $dsn = "mysql:host=".$connectionArgs['host'].";dbname=".$connectionArgs['db_name'].";charset=utf8";
            $opt = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            $this->connection = new PDO($dsn, $connectionArgs['user'], $connectionArgs['pass'], $opt);
        } else {
            throw new Exception('Создай файл конфига БД');
        }
    }

    public function getConnection(): object
    {
        return $this->connection;
    }
}
