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

    public function getDefaultPage($page=1, $limit=5)
    {
        $page = ($page-1)*$limit;
        $pageData = $this->model->prepare("SELECT * FROM goods LIMIT :page, :limit;");
        $pageData->execute(['page'=>$page, 'limit'=>$limit]);
        return $pageData->fetchAll();
    }

    public function getGoodData($id)
    {
        $goodData = $this->model->prepare("SELECT * FROM goods WHERE id=:id");
        $goodData->execute(['id'=> $id]);
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
            $data = [];
        }
        return $data;
    }
}

?>