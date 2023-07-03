<?php

namespace App\Models;

use App\Core\Model;

 abstract class AdditionModel extends Model 
{
    public abstract function add($data);
}