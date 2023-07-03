<?php

namespace App\Core;

    class Validator
    {
        private static $instance;
        protected $paramErrors = [];
        protected $customRules = [];

        private function __construct()
        {
            $parentMethods = ['onlyDigits', 'isEmpty', 'isLetter'];
            foreach($parentMethods as $method) {
                $this->customRules[$method] = [$this, $method];
            }
        }

        public static function getInstance(): object
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
        
        public function getErrors()
        {
            if (isset($this->paramErrors)) {
                return $this->paramErrors;
            }
        }
    }
