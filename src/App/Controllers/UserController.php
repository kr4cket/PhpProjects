<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\UserModel;
use App\Core\View;
use App\Models\GoodsReviewModel;
use App\Views\HtmlView;

class UserController extends Controller
{
    private $model;
    private $reviewModel;

    public function __construct()
    {
        parent::__construct();
        $this->model = new UserModel();
        $this->reviewModel = new GoodsReviewModel();
    }

    public function auth()
    {
        $postData = $_POST;

        if (empty($postData)) {
            $this->template = ['user/authorization', 'Авторизация'];
        } else {

            if ($this->model->checkLogin($postData['userLogin'])) {
                $this->data = ['Неверный логин'];
                $this->template = ['user/authorization', 'Неверный логин'];
            }
            if ($this->model->checkPassword($postData['userLogin'], $postData['userPassword'])) {
                $this->data = ['Неверный пароль'];
                $this->template = ['user/authorization', 'Неверный пароль'];
            }

            if (empty($this->template)) {
                $data = $this->model->getUserData($postData['userLogin']);
                setcookie("name", $data['user_name'], time() + 86000);
                setcookie("surname", $data['user_surname'], time() + 86000);
                setcookie("login", $data['login'], time() + 86000);
                setcookie('role', $data['is_admin'], time() + 86000);
                $this->template = ['user/success_authorization', 'Успешно'];
            }
        }

        return new HtmlView($this->template, $this->data);
    }

    public function add()
    {
        $postData = $_POST;

        if (empty($postData)) {
            $this->template = ['user/registration', 'Регистрация!'];
        } else {

            if ($this->model->isValid($postData)) {
                $this->model->addUser($postData);
                $this->template = ['user/success', 'Успешная регистрация!'];
            } else {
                $this->data = $this->model->getErrors();
                $this->template = ['user/registration', 'Ошибка регистрации!'];
            }

        }
        
        return new HtmlView($this->template, $this->data);
    }

    public function show($page = 1)
    {
        $userData = $_COOKIE;
        $postData = $_POST;

        if($userData) {
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
    
            if($userData['role'] == 1) {
                $this->data = $userData;
                $this->data['reviews'] = $this->reviewModel->getReviewPage($page);
                $this->data['currentPage'] = $page;
                $this->data['pageCount'] = $this->reviewModel->getReviewCount();
                $this->data['action'] = $messageData ?? "";
                $this->template = ['user/admin_profile', 'Админ'];
            } else {
                $this->data = $userData;
                $this->template = ['user/user_profile', 'Профиль'];
            }

        } else {
            $this->template = ['user/logout'];
        }

        return new HtmlView($this->template, $this->data);
    }

    public function logout() 
    {
        setcookie("name", 0, time()-10);
        setcookie("surname", 0, time()-10);
        setcookie("login", 0, time()-10);
        setcookie('role', 0, time()-10);
        $this->template = ['user/logout'];
        return new HtmlView($this->template, $this->data);
    }

}