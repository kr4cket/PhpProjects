<?php

namespace App\Validators;

use App\Core\Validator;

class UserValidator extends Validator
{
    private static $instance;

    private function __construct()
    {
        $parentMethods = ['minLength', 'isPhoneNumber'];
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
        if (strlen($userInput) < 6) {
            return "Поле должно быть длиннее 6 символов!\n";
        }
    }

    protected function isPhoneNumber($userInput)
    {
        if (!preg_match('~^(?:\+7|8)\d{10}$~', $userInput)) {
            return "Поле введено некорректно!\n";
        }
    }

}