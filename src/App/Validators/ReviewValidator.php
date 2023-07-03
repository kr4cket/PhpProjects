<?php

namespace App\Validators;

use App\Core\Validator;

class ReviewValidator extends Validator
{
    private static $instance;

    private function __construct()
    {
        $parentMethods = ['minLength', 'isPhoneNumber', 'isChecked'];
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

    protected function isChecked($userInput)
    {
        if ($userInput <= 0) {
            return "Поле не выбрано!\n";
        }
    }

    protected function isPhoneNumber($userInput)
    {
        if (!preg_match('~^(?:\+7|8)\d{10}$~', $userInput)) {
            return "Поле введено некорректно!\n";
        }
    }

    protected function minLength($userInput)
    {
        if (strlen($userInput) < 3) {
            return "Поле должно быть длиннее 5 символов!\n";
        }
    }

}