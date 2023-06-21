<?php 
namespace App\Core;

class View 
{
    public function __construct()
    {

    }

    public function generate($content, $data)
    {
        include VIEW_PATH.$content.'_view.php';
    }
}
?>