<?php 
    namespace App\Models;
    use \App\Core\Model;
    use \App\Models\GoodsReviewModel;
    use \App\Models\GoodsTypeModel;
    use \App\Models\GoodsManufactureModel;


    class GoodsModel extends Model 
    {
        private $type;
        private $manufacture;

        public function __construct()
        {
            parent::__construct();
            $this->type = new GoodsTypeModel();
            $this->manufacture = new GoodsManufactureModel();
        }

        public function getDefaultPage($startsWith, $finishAt)
        {
            $pageData = $this->db->prepare("SELECT * FROM goods WHERE id BETWEEN $startsWith and $finishAt");
            $pageData->execute();
            return $pageData->fetchAll();
        }

        public function getGoodData($id)
        {
            $goodData = $this->db->prepare("SELECT * FROM goods WHERE id=$id");
            $goodData->execute();
            $goodData = $this->modifyData($goodData->fetch());
            return $goodData;
        }

        private function modifyData($data)
        {
            if ($data) {
                $data['type'] = $this->type->getTypeById($data['type_id']);
                $data['manufacture'] = $this->manufacture->getManufactureById($data['manufacture_id']);
                unset( $data['manufacture_id'],  $data['type_id']);
            }else {
                $data = "";
            }
            return $data;
        }
    }

?>