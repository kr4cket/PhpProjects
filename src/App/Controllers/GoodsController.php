<?php
namespace App\Controllers;

use App\Models\GoodsModel;
use App\Models\GoodsReviewModel;
use App\Core\Controller;
use App\Core\View;
use App\Models\UserModel;
use App\Views\HtmlView;

class GoodsController extends Controller
{
    private $model;
    private $reviews;
    private $user;

    public function __construct()
    {
        parent::__construct();
        $this->model = new GoodsModel();
        $this->reviews = new GoodsReviewModel();
        $this->user = new UserModel();
    }

    public function show($productId): View
    {
        $this->data = $this->model->getGoodData($productId);
        if ($this->data) {
            $this->data['reviews'] = $this->reviews->getReviews($productId);
            $this->template = ['goods/goods', $this->data['name']];
        } else {
            $this->template = 'not_found';
            $this->data = 'с товаром';
        }

        $this->data['isAuth'] = $this->user->isAuthorized();

        return new HtmlView($this->template, $this->data);
    }

    public function add()
    {
        $postData = $_POST;
        if (empty($postData)) {
            $this->data = $this->model->getFormData();
            $this->template = ['goods/add_goods', 'Добавить товар'];
        } else {

            if ($this->model->isValid($postData)) {
                $this->model->addGoodData($postData);
                $this->data = $postData['goodName'];
                $this->template = ['goods/success', 'Успех'];
            }
            else {
                $this->data = $this->model->getFormData($postData);
                $this->template = ['goods/add_goods', 'Добавить товар'];
            }

        }

        $this->data['isAuth'] = $this->user->isAuthorized();

        return new HtmlView($this->template, $this->data);
    }

}
