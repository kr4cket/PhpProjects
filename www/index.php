<?php

    class Validator
    {
        protected $paramErrors = [];
        protected $customRules = [];
        protected static $_instance;

        private function __construct()
        {
            $parentMethods = array('onlyDigits', 'isEmpty', 'isLetter', 'minLength');
            foreach($parentMethods as $method) {
                $this->customRules[$method] = [$this, $method];
            }
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
        protected $valid = false;
        protected $validator;
        protected $data;
        public abstract function isValid();

        public function __construct($requestData)
        {
            $this->data = $requestData;
            $this->validator = Validator::getInstance();
            $this->validateParams = $this->getNonEmptyParams();
        }


        private function getNonEmptyParams()
        {
            $newParamData = [];
            $ruleKeys = array_keys($this->paramRules);
            foreach ($ruleKeys as $ruleKey) {
                $newParamData[$ruleKey] = $this->data[$ruleKey];
            }
            return $newParamData;
        }

        public function validation($validateParams)
        {
            foreach ($validateParams as $type => $param) {
                $this->validator->validate($this->paramRules[$type], $param);
            }
            return !empty($this->validator->getErrors());
        }

        public function getFormData()
        {
            $params = [];
            if (!$this->valid) {
                foreach ($this->data as $dataKey => $dataElement) {
                    $params[$dataKey] = htmlspecialchars($dataElement, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401, "UTF-8");
                }
            } else {
                foreach ($this->data as $dataKey => $dataElement) {
                    $params[$dataKey] = "";
                }
            }
            return $params;
        }
    }

    class FormClass extends MainForm
    {
        private const ADMIN_EMAIL = "koreshkov200@mail.ru";
        protected $paramRules = array(
            "name" => ["isEmpty","minLength","isLetter"],
            "phoneNumber" => ["isEmpty", "onlyDigits"]
        );

        private function sendMessage()
        {
            $userName = $this->data['name'];
            $userSurname = $this->data['surname'];
            $userPhone = $this->data['phoneNumber'];
            
            $userMessage = base64_encode($this->data['message']);
    
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
                $this->sendMessage();
                $this->valid = true;
                return ["Сообщение отправлено, спасибо за отзыв =)"];
            }
        }
    }

    class DB 
    {
        protected static $_instance;
        private function __construct() 
        {

        }

        public static function getInstance() 
        {
            if (self::$_instance === null) {
                self::$_instance = new self;
            }    
            return self::$_instance;
        }

        public function dbConnect()
        {
            $connectionArgs = parse_ini_file("db_connect.ini");
            try{
                $connection = new PDO("mysql:host=".$connectionArgs['host'].";dbname=".
                $connectionArgs['db_name'], $connectionArgs['user'], $connectionArgs['pass']);
            }
            catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            }

            if ($connection) {
                echo "good";
            } else {
                echo "bad";
            }
        }

        
    }

    $messages = [];
    $formData = array(
        'surname' => '',
        'name' => '',
        'phoneNumber' => '',
        'message' => ''
    );
    if (isset($_POST['sendButton'])) {
        $form = new FormClass($_POST);
        $messages = $form->isValid();
        $formData = $form->getFormData();
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
        foreach ($messages as $message) {
        echo($message); 
        }
    } 
    $db = DB::getInstance();
    $db->dbConnect();

?>

<h3>Обратная связь</h3>
<form method="post">
    <h5>Фамилия</h5>
    <input type="text" name="surname" placeholder="Фамилия.." value="<?= $formData["surname"];?>">
	<br>
	<h5>Имя</h5>
    <input type="text" name="name" required placeholder="Имя.." value="<?=  $formData["name"];?>">
	<br>
	<h5>Номер телефона</h5>
    <input type="text" name="phoneNumber" required placeholder="Номер телефона.." value="<?= $formData["phoneNumber"];?>">
	<br>
	<h5>Отзыв</h5>
    <input type="text" name="message" placeholder="Отзыв.." value="<?= $formData["message"];?>">
	<br>
	<br>
	<input type="submit" name="sendButton" value="Отправить">
</form>

</body>

</html>
