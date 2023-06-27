<?php
namespace App\Views;
use App\Core\View;

class PrepareHtmlView extends View
{

    public function __construct($template, $data=[])
    {
        $this->data = $data;
        $this->template = $template;
    }

    public function render()
    {
        $data = $this->data;
        include VIEW_PATH.$this->template."_view.php";
    }

}
