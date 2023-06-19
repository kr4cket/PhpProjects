<?php

    class Validator
    {
        protected $paramErrors = [];
        protected $customRules = [];
        protected static $instance;

        private function __construct()
        {
            $parentMethods = ['onlyDigits', 'isEmpty', 'isLetter', 'minLength'];
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
        protected $db;
        public abstract function isValid();

        public function __construct($requestData)
        {
            $this->data = $this->isEmpty($requestData);
            $this->validator = Validator::getInstance();
            $this->validateParams = $this->getNonEmptyParams();
            $this->db = DB::getInstance();
        }

        private function isEmpty($data) 
        {
            if (empty($data)){
                return [
                    'surname' => '',
                    'name' => '',
                    'phoneNumber' => '',
                    'message' => '',
                    'list' => ''
                ];
            } 
            return $data;
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
            $params['list'] = $this->db->getSelectData();
            return $params;
        }
    }

    class FormClass extends MainForm
    {
        protected $paramRules = [
            "name" => ["isEmpty","minLength","isLetter"],
            "phoneNumber" => ["isEmpty", "onlyDigits"]
        ];

        private function sendReview()
        {
            $insertData = [
                'user_name' => $this->data['name'],
                'user_surname' => $this->data['surname'],
                'user_phone_number' => $this->data['phoneNumber'],
                'theme_id' => $this->data['list'],
                'user_message' => $this->data['message'],
            ];
            $this->db->addReview($insertData);
    
        }

        public function isValid()
        {
            if ($this->validation($this->validateParams)) {
                return $this->validator->getErrors();
            } else {
                $this->sendReview();
                $this->valid = true;
                return ["Сообщение отправлено, спасибо за отзыв =)"];
            }
        }
    }

    class DB 
    {
        protected static $instance;
        protected $connection;
        private function __construct() 
        {
            $this->dbConnect();
        }

        public static function getInstance() 
        {
            if (self::$instance === null) {
                self::$instance = new self;
            }    
            return self::$instance;
        }

        private function dbConnect()
        {
            $connectionArgs = parse_ini_file('../configs/db_connect.ini');
            $dsn = "mysql:host=".$connectionArgs['host'].";dbname=".$connectionArgs['db_name'].";charset=utf8";
            $opt = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            $this->connection = new PDO($dsn, $connectionArgs['user'], $connectionArgs['pass'], $opt);
        }


        public function getSelectData()
        {
            $selectNames = $this->connection->query('SELECT * from themes');
            return $selectNames->fetchAll();
        }
        
        public function addReview($userData)
        {
            $statement = $this->connection->prepare('INSERT INTO user_reviews 
            (user_name, user_surname, user_phone_number, theme_id, user_message) VALUES 
            (:user_name, :user_surname, :user_phone_number, :theme_id, :user_message);');
            $statement->execute( [
                'user_name' => $userData['user_name'],
                'user_surname' => $userData['user_surname'],
                'user_phone_number' => $userData['user_phone_number'],
                'theme_id' => $userData['theme_id'],
                'user_message' => $userData['user_message']
            ]);

        }
    }


    $messages = [];
    if (isset($_POST['sendButton'])) {
        $form = new FormClass($_POST);
        $messages = $form->isValid();
        $formData = $form->getFormData();
    } else {
        $form = new FormClass($_GET);
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
    <select name="list">
        <?php 
            foreach ($formData["list"] as $value) {?>
            <option value=<?= $value['id'];?>><?= $value['theme_type'];?></option>
        <?php }?>
    </select>
    <br>
    <input type="text" name="message" placeholder="Отзыв.." value="<?= $formData["message"];?>">
	<br>
	<br>
	<input type="submit" name="sendButton" value="Отправить">
</form>

</body>

</html>
