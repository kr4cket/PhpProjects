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
        return $this->validator->getErrors();
    }

    public function isAuth($postData)
    {
        $request = $this->model->prepare("SELECT id, login, password FROM users WHERE login=:login");
        $request->execute(['login' => $postData['userLogin']]);
        $userData = $request->fetch();

        if (empty($userData)) {
            return null;
        }
        if (!password_verify($postData['userPassword'], $userData['password'])) {
            return null;
        }

        return $userData['id'];
    }

    public function startSession($id)
    {
        $_SESSION["id"] = $id;
    }

    public function isAuthorized(): bool
    {
        return isset($_SESSION['id']);
    }

    public function isAdmin($id): bool
    {
        $isAdmin = $this->model->prepare("SELECT is_admin FROM users WHERE id=:id");
        $isAdmin->execute(['id'=>$id]);
        if (self::isAuthorized() && $isAdmin->fetch()['is_admin'] == 1) {
            return true;
        } else {
            return false;
        }

    }

    public function authorize($userData)
    {
        $userId = $this->isAuth($userData);

        if ($userId) {
            $this->startSession($userId);
            $template = ['user/success_authorization', 'Успешно'];
        } else {
            $template = ['user/authorization', 'Неверный логин или пароль'];
        }

        return $template;
    }

    public function dieSession()
    {
        unset($_SESSION['id']);
    }

    public function getAdminData($postData, $page, $id): array
    {
        if ($postData) {
            $reviewId = $postData['id'];
            $type = $postData['action'];

            if ($type == "Одобрить") {
                $this->reviewModel->allowReview($reviewId);
                $messageData = "Комментарий одобрен";
            } else {
                $this->reviewModel->deleteReview($reviewId);
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
