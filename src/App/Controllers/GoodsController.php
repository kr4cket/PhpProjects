<?php
namespace App\Controllers;

use App\Models\GoodsModel;
use App\Models\GoodsReviewModel;
use App\Core\Controller;
use App\Core\View;
use App\Views\HtmlView;

class GoodsController extends Controller
{
    private $model;
    private $reviews;

    public function __construct()
    {
        parent::__construct();
        $this->model = new GoodsModel();
        $this->reviews = new GoodsReviewModel();
    }

    public function show($productId): View
    {
        $this->data = $this->model->getGoodData($productId);
        if ($this->data) {
            $this->data['reviews'] = $this->reviews->getReviews($productId);
            $this->template = ['goods', $this->data['name']];
        } else {
            $this->template = 'not_found';
            $this->data = 'с товаром';
        }

        return new HtmlView($this->template, $this->data);
    }

    public function add()
    {
        $postData = $_POST;
        if (empty($postData)) {
            $this->data = $this->model->getFormData();
            $this->template = ['add_goods', 'Добавить товар'];
        } else {
            if ($this->model->isValid($postData)) {
                $this->model->addGoodData($postData);
                $this->data = $postData['goodName'];
                $this->template = ['success', 'Успех'];
            }
            else {
                $this->data = $this->model->getFormData($postData);
                $this->template = ['add_goods', 'Добавить товар'];
            }
        }

        return new HtmlView($this->template, $this->data);
    }

}
