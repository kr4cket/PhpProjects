<?php
namespace App\Core;
use \App\Core\DataBaseConnection;
use \App\Core\Validator;

class Model
{
    protected $model;
    protected $validator;

    public function __construct()
    {
        $this->model = DataBaseConnection::getInstance()->getConnection();
        $this->validator = Validator::getInstance();
    }
}
