<?php 
    namespace App\Controllers;
    use App\Models\GoodsReviewModel;

    class ReviewsController
    {
        private $db;
        public function __construct()
        {
            $this->db = new GoodsReviewModel();
            echo "REVIEW";
        }

        public function add() 
        {
            print_r('adding new review');
            return $this->db->getDefaultPage(1,6);
        }
    }


?>