<?php
namespace App\Views;
use App\Core\View;

class HtmlView extends View
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
        include VIEW_PATH.$this->template[0]."_view.php";
        $templateData['body'] = ob_get_contents();
        $templateData['head'] = $this->template[1];
        ob_end_clean();

        include VIEW_PATH.'layout_view.php';
    }
}
