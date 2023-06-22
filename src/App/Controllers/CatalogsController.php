<?php 
namespace App\Controllers;
use App\Models\GoodsModel;
use App\Core\Controller;

class CatalogsController extends Controller
{
    private $model;

    public function __construct()
    {
        parent::__construct();
        $this->model = new GoodsModel();
    }

    public function index($page=1, $orderType='default')
    {
        if ($this->model->existPage($page)) {
            $data = $this->model->getPage($page, $orderType);
            $this->view->render('catalog', $data);
        } else {
            $this->view->render('not_found', 'товаров');
        }
    }
}

?>