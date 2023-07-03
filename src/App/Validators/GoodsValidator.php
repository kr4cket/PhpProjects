<?php

namespace App\Validators;

use App\Core\Validator;

class GoodsValidator extends Validator
{
    private static $instance;

    private function __construct()
    {
        $parentMethods = ['minLength', 'minCost', 'isChecked'];
        foreach($parentMethods as $method) {
            $this->customRules[$method] = [$this, $method];
        }
    }

    public static function getInstance(): object
    {
        parent::getInstance();
        if (self::$instance === null) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    protected function minLength($userInput)
    {
        if (strlen($userInput) < 3) {
            return "Поле должно быть длиннее 3 символов!\n";
        }
    }

    protected function minCost($userInput)
    {
        if ($userInput < 1000) {
            return "Минимальная цена товара должна быть больше 1000 рублей!\n";
        }
    }

    protected function isChecked($userInput)
    {
        if ($userInput <= 0) {
            return "Поле не выбрано!\n";
        }
    }
}