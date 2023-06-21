<?php 
    namespace App\Core;
    use \App\Core\DataBaseConnection;

    class Model 
    {
        protected $db;

        public function __construct()
        {
            $this->db = DataBaseConnection::getInstance()->getConnection();
        }


    }

?>