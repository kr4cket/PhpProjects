<?php
namespace App\Controllers;
use App\Core\Controller;
use App\Views\HtmlView;

class NotFoundController extends Controller
{
    public function index($data)
    {
        $this->template = ['not_found', 'Ошибка'];
        return new HtmlView($this->template ,$data);
    }

}
