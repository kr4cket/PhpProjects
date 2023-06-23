<?php
namespace App\Views;
use App\Core\View;

class HtmlView extends View
{

    public function __construct($content, $data=[])
    {
        $this->data = $data;
        $this->content = $content;
    }

    public function render()
    {
        $data = $this->data;
        $content = $this->content;
        include VIEW_PATH.'layout_view.php';
    }
}
