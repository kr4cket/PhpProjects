<?php
namespace App\Controllers;
use App\Core\Controller;
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

    public function add($productId)
    {
        $postData = $_POST;

        if (!$this->goodsModel->existProductId($productId)) {
            $this->content = 'not_found';
            $this->data = 'с товаром';
            return new HtmlView($this->content, $this->data);
        }

        if (!empty($postData)) {
            $postData['id'] = $productId;
            if ($this->model->isValid($postData)) {
                $this->model->addGoodData($postData);
                $this->content = 'success_review';
                return new HtmlView($this->content, $this->data);
            }
        } 

        $this->data = $this->model->getFormData($postData);
        $this->content = 'add_review';
        return new HtmlView($this->content, $this->data);

    }


    public function getReviewsById()
    {

    }
}
