<?php 
namespace App\Core;
use \PDO;

class DataBaseConnection
{
    private static $instance;
    private $connection;

    private function __construct()
    {
        $this->dbConnect();
    }

    public static function getInstance() 
    {
        if (self::$instance === null) {
            self::$instance = new self;
        }    
        return self::$instance;
    }

    private function dbConnect() 
    {
        $connectionArgs = parse_ini_file(CONFIG_PATH.'db_connect.ini');
        $dsn = "mysql:host=".$connectionArgs['host'].";dbname=".$connectionArgs['db_name'].";charset=utf8";
        $opt = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        $this->connection = new PDO($dsn, $connectionArgs['user'], $connectionArgs['pass'], $opt);
    }

    public function getConnection()
    {
        return $this->connection;
    }
}
?>