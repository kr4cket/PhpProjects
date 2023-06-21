<?php 
namespace App\Controllers;
use App\Models\GoodsModel;
use App\Core\Controller;

class GoodsController extends Controller
{
    private $model;
    public function __construct()
    {
        parent::__construct();
        $this->model = new GoodsModel();
    }

    public function show($productId)
    {
        $data = $this->model->getGoodData($productId);
        if($data) {
            $this->view->generate('goods', $data);
            return;
        }
        $this->view->generate('not_found', "с товаром");
        return;
    }

    public function add() 
    {
        print_r('adding new good');
        return $this->model->getDefaultPage(1,6);
    }
}

?>