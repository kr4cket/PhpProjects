<?php
namespace App\Controllers;
use App\Core\Controller;
use App\Views\HtmlView;

class NotFoundController extends Controller
{
    public function index($data)
    {
        $this->content = 'not_found';
        return new HtmlView($this->content ,$data);
    }

}
