<?php
    namespace App\Forms;

    class FormClass extends MainForm
    {
        protected $paramRules = [
            "name" => ["isEmpty","minLength","isLetter"],
            "phoneNumber" => ["isEmpty", "onlyDigits"],
            "list" => ['isExist']
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
?>