<?php
namespace App\Controllers;
use App\Models\GoodsModel;
use App\Core\Controller;
use App\Views\HtmlView;

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
            $this->data = $this->model->getPage($page, $orderType);
            $this->content = 'catalog';
        } else {
            $this->data = 'с товаром';
            $this->content = 'not_found';
        }
        return new HtmlView($this->content, $this->data);
    }
}
