<?php 
    namespace App\Models;
    use \App\Core\Model;

    class GoodsReviewModel extends Model 
    {
        public function getDefaultPage($startsWith, $finishAt)
        {
            $pageData = $this->db->prepare("SELECT * FROM goods WHERE id BETWEEN $startsWith and $finishAt");
            $pageData->execute();
            return $pageData->fetchAll();
        }

    }

?>