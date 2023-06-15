<?php

    abstract class UniversalValidator
    {
        protected $paramErrors = [];
        public abstract function validate($rules, $userInput);

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

    class FormValidator extends UniversalValidator
    {
        public function validate($rules, $validateParam)
        {
            foreach ($rules as $rule)
            {
                if (method_exists($this, $rule)) {
                    $error = $this->$rule($validateParam);
                    if (isset($error)) {
                        $this->paramErrors[] = $error;
                    }
                }
            }
        }
    }

    abstract class MainForm
    {
        protected $paramRules;
        protected $validateParams;
        protected $validator;

        function __construct()
        {
            $this->paramRules = array();
            $this->validateParams = array();
            $this->validator = new FormValidator();
        }
        public abstract function isCorrect();

        public function Validation($validateParams)
        {
            foreach ($validateParams as $type => $param) {
                $this->validator->validate($this->paramRules[$type], $param);
            }
        }


    }

    class FormClass extends MainForm
    {
        private const ADMIN_EMAIL = "koreshkov200@mail.ru";
        private $data;

        function __construct($requestData)
        {
            $this->validator = new FormValidator();
            $this->paramRules = array(
                "name" => ["isEmpty","minLength","isLetter"],
                "phoneNumber" => ["isEmpty", "onlyDigits"]
            );
            $this->validateParams = array(
                "name" => $requestData['name'],
                "phoneNumber" => $requestData['phoneNumber']
            );
            $this->data = $requestData;
        }

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

        public function isCorrect()
        {
            $this->Validation($this->validateParams);
            if ($this->validator->getErrors()) {
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
        $messages = $form->isCorrect();
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
