<?php
namespace App\Controllers;
use App\Models\GoodsModel;
use App\Models\GoodsManufactureModel;
use App\Models\GoodsTypeModel;
use App\Core\Controller;
use App\Views\HtmlView;

class GoodsController extends Controller
{
    private $model;
    private $type;
    private $manufacture;

    public function __construct()
    {
        parent::__construct();
        $this->model = new GoodsModel();
        $this->type = new GoodsTypeModel();
        $this->manufacture = new GoodsManufactureModel();
    }

    public function show($productId)
    {
        $this->data = $this->model->getGoodData($productId);
        if($this->data) {
            $this->content = 'goods';
        } else {
            $this->content = 'not_found';
            $this->data = 'с товаром';
        }

        return new HtmlView($this->content, $this->data);
    }

    public function add()
    {
        $postData = $_POST;
        if (empty($postData)) {
            $this->data = $this->model->getFormData();
            $this->content = 'add_goods';
        } else {

            if ($this->model->isValid($postData)) {
                $this->model->addGoodData($postData);
                $this->data = $postData['goodName'];
                $this->content = 'success';
            }
            else {
                $this->data = $this->model->getFormData($postData);
                $this->content = 'add_goods';
            }
        }

        return new HtmlView($this->content, $this->data);
    }


}
