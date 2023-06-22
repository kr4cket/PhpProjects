<?php
    namespace App\Core;
    
    class Validator
    {
        protected $paramErrors = [];
        protected $customRules = [];
        protected static $instance;

        private function __construct()
        {
            $parentMethods = ['onlyDigits', 'isEmpty', 'isLetter', 'minLength', 'isPositiveNumber', 'isChecked', 'isPhoneNumber'];
            foreach($parentMethods as $method) {
                $this->customRules[$method] = [$this, $method];
            }
        }

        public static function getInstance() 
        {
            if (self::$instance === null) {
                self::$instance = new self;
            }    
            return self::$instance;
        }

        public function validate($rules, $validateParam)
        {
            foreach ($rules as $rule)
            { 
                if (array_key_exists( $rule, $this->customRules) && is_callable($this->customRules["$rule"])) {
                    $error = $this->customRules["$rule"]($validateParam);
                    if (isset($error)) {
                        $this->paramErrors[] = $error;
                    }
                }
            }
        }

        public function registerValidator($name, callable $func)
        {
            $this->customRules[$name] = $func;
        }
        protected function onlyDigits($userInput)
        {
            if (!preg_match("/[\d]+/", $userInput)) {
                return "Поле введено некорректно!\n";
            }
        }
        protected function isChecked($userInput)
        {
            if ($userInput <= 0) {
                return "Поле не выбрано!\n";
            }
        }
        protected function isPositiveNumber($userInput)
        {
            if ($userInput < 1000) {
                return "Минимальная цена товара должна быть больше 1000 рублей!\n";
            }
        }
        protected function isEmpty($userInput)
        {
            if (empty($userInput)) {
                return "Поле должно быть заполнено!\n";
            }
        }
        protected function isLetter($userInput)
        {
            if (preg_match("/[\d]+/", $userInput)) {
                return "Поле должно содержать только буквы!\n";
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
                return "Поле должно быть длиннее 3х символов!\n";
            }
        }

        public function getErrors()
        {
            if (isset($this->paramErrors)) {
                return $this->paramErrors;
            }
        }
    }
?>