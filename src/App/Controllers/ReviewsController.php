<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\View;
use App\Models\GoodsModel;
use App\Models\GoodsReviewModel;
use App\Views\HtmlView;

class ReviewsController extends Controller
{
    private $model;
    private $goodsModel;

    public function __construct()
    {
        parent::__construct();
        $this->model = new GoodsReviewModel();
        $this->goodsModel = new GoodsModel();
    }

    public function add($productId): View
    {
        $postData = $_POST;

        if (!$this->goodsModel->existProductId($productId)) {
            $this->template = ['not_found', 'Ошибка'];
            $this->data = 'с товаром';

            return new HtmlView($this->template, $this->data);
        }

        if (!empty($postData)) {
            $postData['id'] = $productId;
            if ($this->model->isValid($postData)) {
                $this->model->addReview($postData);
                $this->template = ['goods/success_review', 'Успех'];

                return new HtmlView($this->template, $this->data);
            }
        } 

        $this->data = $this->model->getFormData($postData);
        $this->template = ['goods/add_review','Добавить отзыв'];

        return new HtmlView($this->template, $this->data);
    }


    public function getReviewsById()
    {

    }
}
