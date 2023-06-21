<?php 
    namespace App\Core;
    use App\Core\View;

    class Controller
    {
        protected $view;

        public function __construct()
        {
            $this->view = new View();
        }


    }


?>