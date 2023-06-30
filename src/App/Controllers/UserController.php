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

    public function __construct()
    {
        parent::__construct();
        $this->model = new UserModel();
    }

    public function auth()
    {
        $postData = $_POST;

        if (empty($postData)) {
            $this->template = ['user/authorization', 'Авторизация'];
        } else {
            if ($this->model->isAuth($postData)) {
                $this->model->startSession($postData['userLogin']);
                $this->template = ['user/success_authorization', 'Успешно'];
            } else {
                $this->template = ['user/authorization', 'Неверный логин или пароль'];
            }
        }

        return new HtmlView($this->template, $this->data);
    }

    public function add()
    {
        $postData = $_POST;

        if (empty($postData)) {
            $this->template = ['user/registration', 'Регистрация'];
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
        $sessionData = $_SESSION['id'];
        $postData = $_POST;

        if (UserModel::isCurrent()) {
            
            if ($this->model->isAdmin($sessionData)) {
                $this->data = $this->model->getAdminData($postData,$page, $sessionData);
                $this->template = ['user/admin_profile', 'Админ'];
            } else {
                $this->data = $this->model->getUserData($sessionData);
                $this->template = ['user/user_profile', 'Профиль'];
            }
        
        } else {
            $this->template = ['user/logout'];
        }

        return new HtmlView($this->template, $this->data);
    }

    public function logout() 
    {
        $this->model->dieSession();
        $this->template = ['user/logout'];

        return new HtmlView($this->template, $this->data);
    }

}