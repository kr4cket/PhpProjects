<?php 
    namespace App\Controllers;
    use App\Models\GoodsModel;
    use App\Core\Controller;

    class CatalogsController extends Controller
    {
        private $db;

        public function __construct()
        {
            parent::__construct();
            $this->db = new GoodsModel();
        }

        public function index()
        {
            $data = $this->db->getDefaultPage(1,6);
            $this->view->generate('catalog', $data);
        }

    }


?>