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
        ob_start();
        $data = $this->data;
        include VIEW_PATH.$this->template."_view.php";
        $pages = ob_get_contents();
        ob_end_clean();

        return $pages;
    }

}
