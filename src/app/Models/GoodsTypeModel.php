<?php 
    namespace App\Models;
    use \App\Core\Model;

    class GoodsTypeModel extends Model 
    {
        public function getTypeById($id)
        {
            $data = $this->db->prepare("SELECT type_name FROM goods_type WHERE id=$id");
            $data->execute();
            return $data->fetch()['type_name'];
        }

    }

?>