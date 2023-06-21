<?php 
namespace App\Core;
use \App\Core\DataBaseConnection;

class Model 
{
    protected $model;

    public function __construct()
    {
        $this->model = DataBaseConnection::getInstance()->getConnection();
    }
}
?>