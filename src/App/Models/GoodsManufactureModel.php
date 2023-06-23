<?php
namespace App\Models;
use \App\Core\Model;

class GoodsManufactureModel extends Model
{
    public function getManufactureById($id)
    {
        $data = $this->model->prepare("SELECT manufacture_name FROM goods_manufacture WHERE id=:id");
        $data->execute(['id'=>$id]);
        return $data->fetch()['manufacture_name'];
    }

    public function getData()
    {
        $data = $this->model->prepare("SELECT * FROM goods_manufacture");
        $data->execute();
        return $data->fetchAll();
    }

}
