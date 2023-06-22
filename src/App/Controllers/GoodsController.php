<?php 
namespace App\Controllers;
use App\Models\GoodsModel;
use App\Models\GoodsManufactureModel;
use App\Models\GoodsTypeModel;
use App\Core\Controller;

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
        $data = $this->model->getGoodData($productId);
        if($data) {
            $this->view->render('goods', $data);
            return;
        }
        $this->view->render('not_found', "с товаром");
        return;
    }

    public function add() 
    {
        $postData = $_POST;
        if (empty($postData)) {
            $data = $this->model->getEmptyFormData();
            $this->view->render('add_goods', $data);
            return;
        } 
        $this->validate($postData);
    }

    private function validate($postData) 
    {
        if ($this->model->isValid($postData)) {
            $this->model->addGoodData($postData);
            $this->view->render('success', $postData['goodName']);
            return;
        }
        $data = $this->model->getFormData($postData);
        $this->view->render('add_goods', $data);
    }

}

?>