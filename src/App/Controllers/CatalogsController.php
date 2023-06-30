<?php
namespace App\Controllers;

use App\Models\GoodsModel;
use App\Core\Controller;
use App\Core\View;
use App\Models\UserModel;
use App\Views\HtmlView;

class CatalogsController extends Controller
{
    private $model;
    private $user;

    public function __construct()
    {
        parent::__construct();
        $this->model = new GoodsModel();
        $this->user = new UserModel();
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
                $this->template = ['catalog/catalog', 'Каталог товаров'];
            } else {
                $this->data = $this->model->getPage($page, $orderType);
                $this->template = ['catalog/catalog', 'Каталог товаров'];
            }

        } else {
            $this->data['message'] = 'с товаром';
            $this->template = ['not_found', 'Ошибка'];
        }

        $this->data['isAuth'] = $this->user->isAuthorized();

        return new HtmlView($this->template, $this->data);
    }
}
