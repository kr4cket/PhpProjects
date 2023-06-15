<?php

use Validator as GlobalValidator;

    class Validator
    {
        protected $paramErrors = [];
        protected static $_instance;
        protected static $customRules = [];

        private function __construct()
        {
            $this->customRules['onlyDigits'] = [$this, 'onlyDigits'];
            $this->customRules['isEmpty'] = [$this, 'isEmpty'];
            $this->customRules['isLetter'] = [$this, 'isLetter'];
            $this->customRules['minLength'] = [$this, 'minLength'];
        }

        public static function getInstance() 
        {
            if (self::$_instance === null) {
                self::$_instance = new self;
            }    
            return self::$_instance;
        }

        public function validate($rules, $validateParam)
        {
            foreach ($rules as $rule)
            { 
                if (array_key_exists( $rule, $this->customRules)) {
                    $this->paramErrors[] = $this->customRules["$rule"]($validateParam);
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
            if (!preg_match('~^(?:\+7|8)\d{10}$~', $userInput)) {
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


    abstract class MainForm
    {
        protected $paramRules = [];
        protected $validateParams = [];
        protected $validator;
        protected $validator1;
        protected $data;
        public abstract function isValid();

        public function __construct($requestData)
        {
            $this->validator = Validator::getInstance();
            $this->validateParams = $this->getNonEmptyParams($requestData);
            $this->data = $requestData;
            Validator::getInstance()->registerValidator("isAdmin", 
            function($input)
            {
                if($input != "Admin") {
                    return "Я тебя не знаю, мне нужен Админ!";
                }
            });
        }

        private function getNonEmptyParams($data)
        {
            $newParamData = [];
            $ruleKeys = array_keys($this->paramRules);
            foreach ($ruleKeys as &$ruleKey) {
                $newParamData[$ruleKey] = $data[$ruleKey];
            }
            return $newParamData;
        }

        public function validation($validateParams)
        {
            foreach ($validateParams as $type => $param) {
                $this->validator->validate($this->paramRules[$type], $param);
            }
            if ($this->validator->getErrors()) {
                return true;
            } else {
                return false;
            }
        }
    }

    class FormClass extends MainForm
    {
        private const ADMIN_EMAIL = "koreshkov200@mail.ru";
        protected $paramRules = array(
            "name" => ["isEmpty","minLength","isLetter", "isAdmin"],
            "phoneNumber" => ["isEmpty", "onlyDigits"]
        );

        private function sendMessage($data)
        {
            $userName = $data['name'];
            $userSurname = $data['surname'];
            $userPhone = $data['phoneNumber'];
            
            $userMessage = base64_encode($data['message']);
    
            $subject_text = "Отзыв пользователя $userName $userSurname Контактный телефон: $userPhone";
            $subject = '=?UTF-8?B?' . base64_encode($subject_text) . '?=';
            $headers = 'Content-Type: text/plain; charset=utf-8' . "\r\n";
            $headers .= 'Content-Transfer-Encoding: base64';
    
            mail(self::ADMIN_EMAIL, $subject, $userMessage, $headers);
        }

        public function isValid()
        {
            if ($this->validation($this->validateParams)) {
                return $this->validator->getErrors();
            } else {
                $this->sendMessage($this->data);
                return ["Сообщение отправлено, спасибо за отзыв =)"];
            }
        }
    }

    $messages = [];
    if (isset($_POST['sendButton'])) {
        $form = new FormClass($_POST);
        $messages = $form->isValid();
    }

?>


<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Обратная связь</title>
</head>
<body>

<?php
    if (isset($messages)) {
        foreach ($messages as &$message) {
        echo($message);
        }
    }
?>

<h3>Обратная связь</h3>

<form method="post">
    <h5>Фамилия</h5>
    <input type="text" name="surname" placeholder="Фамилия..">
	<br>
	<h5>Имя</h5>
    <input type="text" name="name" required placeholder="Имя..">
	<br>
	<h5>Номер телефона</h5>
    <input type="text" name="phoneNumber" required placeholder="Номер телефона..">
	<br>
	<h5>Отзыв</h5>
    <input type="text" name="message" placeholder="Отзыв..">
	<br>
	<br>
	<input type="submit" name="sendButton" value="Отправить">
</form>


</body>

</html>
