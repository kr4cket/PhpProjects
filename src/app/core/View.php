<?php 
    namespace App\Core;

    class View 
    {
        public function __construct()
        {

        }

        public function generate($content, $data)
        {
            include ROOT.'/../src/App/Views/'.$content.'_view.php';
        }
    }
?>