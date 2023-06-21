<?php 
    namespace App\Controllers;
    use App\Models\GoodsModel;
    use App\Core\Controller;

    class GoodsController extends Controller
    {
        private $db;
        public function __construct()
        {
            parent::__construct();
            $this->db = new GoodsModel();
        }

        public function show($productId)
        {
            $data = $this->db->getGoodData($productId[0]);
            if(empty($data)) {
                $this->view->generate('not_found', $data);
                return;
            }
            $this->view->generate('goods', $data);
        }

        public function add() 
        {
            print_r('adding new good');
            return $this->db->getDefaultPage(1,6);
        }
    }


?>