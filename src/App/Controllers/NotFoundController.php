<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\View;
use App\Models\UserModel;
use App\Views\HtmlView;

class NotFoundController extends Controller
{
    private $user;
    public function __construct()
    {
        parent::__construct();
        $this->user = new UserModel();
    }
    public function index($data): View
    {
        $this->data['isAuth'] = $this->user->isAuthorized();
        $this->template = ['not_found', 'Ошибка'];

        return new HtmlView($this->template ,$data);
    }

}
