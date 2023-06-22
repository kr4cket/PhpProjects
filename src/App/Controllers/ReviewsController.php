<?php 
namespace App\Controllers;
use App\Core\Controller;
use App\Models\GoodsModel;
use App\Models\GoodsReviewModel;

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
            $this->view->render("not_found", 'c товаром');
            return;
        }

        if (empty($postData)) {
            $data = $this->model->getEmptyFormData($productId);
            $this->view->render("add_review", $data);
            return;
        }
        $this->validate($postData, $productId);
    }

    private function validate($postData, $productId) 
    {
        $postData['id'] = $productId;
        if ($this->model->isValid($postData)) {
            $this->model->addGoodData($postData);
            $this->view->render('success_review');
            return;
        }
        $data = $this->model->getFormData($postData);
        $this->view->render('add_review', $data);
    }

    public function getData() 
    {
        
    }
}
?>