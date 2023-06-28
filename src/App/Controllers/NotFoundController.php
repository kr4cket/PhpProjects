<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\View;
use App\Views\HtmlView;

class NotFoundController extends Controller
{
    public function index($data): View
    {
        $this->template = ['not_found', 'Ошибка'];

        return new HtmlView($this->template ,$data);
    }

}
