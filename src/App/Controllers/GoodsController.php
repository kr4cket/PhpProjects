<?php
namespace App\Controllers;
use App\Models\GoodsModel;
use App\Models\GoodsManufactureModel;
use App\Models\GoodsTypeModel;
use App\Core\Controller;
use App\Models\GoodsReviewModel;
use App\Views\HtmlView;

class GoodsController extends Controller
{
    private $model;
    private $type;
    private $manufacture;
    private $reviews;

    public function __construct()
    {
        parent::__construct();
        $this->model = new GoodsModel();
        $this->type = new GoodsTypeModel();
        $this->manufacture = new GoodsManufactureModel();
        $this->reviews = new GoodsReviewModel();
    }

    public function show($productId)
    {
        $this->data = $this->model->getGoodData($productId);
        if($this->data) {
            $this->data['reviews'] = $this->prepareReview($this->reviews->getReviews($productId));
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

    private function prepareReview($data) 
    {
        $content = [];
        foreach($data as $review){
            $content[] = html_entity_decode('
            <div>
                <p>'.$review['name'].' '.$review['surname'].'</p>
                <p>'.$review['phone_number'].'</p>
                <p>Оценка: '.$review['rating'].'</p>
                <p>'.$review['review'].'</p>
            </div>
            <br>');
        }
        return implode($content);
    }
}
