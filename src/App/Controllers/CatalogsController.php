<?php
namespace App\Controllers;

use App\Models\GoodsModel;
use App\Core\Controller;
use App\Core\View;
use App\Views\HtmlView;

class CatalogsController extends Controller
{
    private $model;

    public function __construct()
    {
        parent::__construct();
        $this->model = new GoodsModel();
    }

    public function index($page=1, $orderType='', $manufacture='', 
    $goodFilterName='', $minPrice='', $maxPrice='', ...$args): View
    {
        if ($this->model->existPage($page)) {
            $filters = [
                'manufacture'       => $manufacture,
                'goodFilterName'    => $goodFilterName,
                'minPrice'          => $minPrice,
                'maxPrice'          => $maxPrice
            ];
            if ($this->model->isValid($filters)) {
                $this->data = $this->model->getPage($page, $orderType, $filters);
                $this->template = ['catalog', 'Каталог товаров'];
            } else {
                $this->data = $this->model->getPage($page, $orderType);
                $this->template = ['catalog', 'Каталог товаров'];
            }
        } else {
            $this->data = 'с товаром';
            $this->template = ['not_found', 'Ошибка'];
        }

        return new HtmlView($this->template, $this->data);
    }
}
