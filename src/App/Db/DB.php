<?php
    namespace App\Db;
    use \PDO;

    class DB 
    {
        protected static $instance;
        protected $connection;
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
            $connectionArgs = parse_ini_file(PATH_CONFIG . 'db_connect.ini');
            $dsn = "mysql:host=".$connectionArgs['host'].";dbname=".$connectionArgs['db_name'].";charset=utf8";
            $opt = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            $this->connection = new PDO($dsn, $connectionArgs['user'], $connectionArgs['pass'], $opt);
        }


        public function getSelectData()
        {
            $selectNames = $this->connection->query('SELECT * from themes');
            return $selectNames->fetchAll();
        }
        
        public function addReview($userData)
        {
            $statement = $this->connection->prepare('INSERT INTO user_reviews 
            (user_name, user_surname, user_phone_number, theme_id, user_message) VALUES 
            (:user_name, :user_surname, :user_phone_number, :theme_id, :user_message);');
            $statement->execute( [
                'user_name' => $userData['user_name'],
                'user_surname' => $userData['user_surname'],
                'user_phone_number' => $userData['user_phone_number'],
                'theme_id' => $userData['theme_id'],
                'user_message' => $userData['user_message']
            ]);

        }
    }
    ?>