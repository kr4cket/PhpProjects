<?php
namespace App\Core;

use \App\Core\DataBaseConnection;
use \App\Core\Validator;

abstract class Model
{
    protected $model;
    protected $validator;

    public function __construct()
    {
        $this->model = DataBaseConnection::getInstance()->getConnection();
    }

    public abstract function getData(mixed $id); 
}
