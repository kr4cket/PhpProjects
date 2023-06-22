<?php 
namespace App\Controllers;
use App\Core\Controller;

class NotFoundController extends Controller
{

    public function index($data)
    {
        $this->view->render('not_found', $data);
    }


}
?>