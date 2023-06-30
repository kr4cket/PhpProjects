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

    public function checkLogin($login): bool
    {
        $request = $this->model->prepare("SELECT login FROM users WHERE login=:login");
        $request->execute(['login' => $login]);

        return empty($request->fetchAll());
    }

    public function checkPassword($login, $password): bool
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

    public function getUserId($login): int
    {
        $request = $this->model->prepare("SELECT id FROM users WHERE login=:login");
        $request->execute(['login' => $login]);

        return $request->fetch();
    }

    public function isAuth($postData): bool
    {
        if ($this->checkLogin($postData['userLogin'])) {
            return false;
        }
        if ($this->checkPassword($postData['userLogin'], $postData['userPassword'])) {
            return false;
        }
        return true;
    }

    public function startSession($login)
    {
        $data = $this->getUserId($login);
        $_SESSION["id"] = $data['id'];
    }

    public static function isCurrent(): bool
    {
        return isset($_SESSION['id']);
    }

    public function isAdmin($id): bool
    {
        $isAdmin = $this->model->prepare("SELECT is_admin FROM users WHERE id=:id");
        $isAdmin->execute(['id'=>$id]);
        if (self::isCurrent() && $isAdmin->fetch()['is_admin'] == 1) {
            return true;
        } else {
            return false;
        }

    }

    public function dieSession()
    {
        unset($_SESSION['id']);
    }

    public function getAdminData($postData, $page, $id): array
    {
        if ($postData) {
            $key = array_keys($postData)[0];
            $value = $postData[$key];
            if ($value == "Одобрить") {
                $this->reviewModel->allowReview($key);
                $messageData = "Комментарий одобрен";
            } else {
                $this->reviewModel->deleteReview($key);
                $messageData = "Комментарий удален";
            }
        }
        $data = $this->getUserData($id);
        $data['reviews'] = $this->reviewModel->getReviewPage($page);
        $data['currentPage'] = $page;
        $data['pageCount'] = $this->reviewModel->getReviewCount();
        $data['action'] = $messageData ?? "";

        return $data;
    }

    public function getUserData($id): array
    {
        $request = $this->model->prepare("SELECT user_name, user_surname, login FROM users WHERE id=:id");
        $request->execute(['id'=>$id]);

        return $request->fetch();
    }

}