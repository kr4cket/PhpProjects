<?php

namespace App\Models;

use App\Core\Model;
use App\Models\GoodsReviewModel;

class UserModel extends Model
{

    private $reviewModel;
    private $paramRules = [
        'userName'             => ['isEmpty', 'minLength'],
        'userPhone'            => ['isPhoneNumber', 'isEmpty'],
        'userLogin'            => ['isEmpty', 'minLength'],
        'userPassword'         => ['isEmpty', 'minLength']
    ];

    public function __construct()
    {
        parent::__construct();
        $this->reviewModel = new GoodsReviewModel();
    }

    public function checkLogin($login)
    {
        $request = $this->model->prepare("SELECT login FROM users WHERE login=:login");
        $request->execute(['login' => $login]);
        return empty($request->fetchAll());
    }

    public function checkPassword($login, $password)
    {
        $request = $this->model->prepare("SELECT password FROM users WHERE login=:login");
        $request->execute(['login' => $login]);
        $hash = $request->fetch()['password'];
        return !password_verify($password, $hash);
    }

    public function addUser($userData) 
    {
        $request = $this->model->prepare("INSERT INTO users (login, password, user_name, user_surname)
        VALUES (:login, :password, :user_name, :user_surname);");
        $request->execute([
            'login'        => $userData['userLogin'],
            'password'     => password_hash($userData['userPassword'], PASSWORD_DEFAULT),
            'user_name'    => $userData['userName'],
            'user_surname' => $userData['userSurname'] ?? ''
        ]);
    }

    public function isValid($validateData): bool
    {
        foreach ($validateData as $type => $param) {
            if (array_key_exists($type, $this->paramRules)) {
                $this->validator->validate($this->paramRules[$type], $param);
            }
        }

        return empty($this->validator->getErrors());
    }

    public function getErrors() 
    {
        $this->validator->getErrors();
    }

    public function getUserData($login)
    {
        $request = $this->model->prepare("SELECT * FROM users WHERE login=:login");
        $request->execute(['login' => $login]);
        return $request->fetch();
    }

}