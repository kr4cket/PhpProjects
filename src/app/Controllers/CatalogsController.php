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

    public function index()
    {
        $data = $this->model->getDefaultPage(1,6);
        $this->view->generate('catalog', $data);
    }

}

?>