<?php
namespace App\Models;

use \App\Core\Model;

class GoodsTypeModel extends Model
{
    public function getTypeById($id): string
    {
        $data = $this->model->prepare("SELECT type_name FROM goods_type WHERE id=:id");
        $data->execute(['id'=>$id]);
        return $data->fetch()['type_name'];
    }

    public function getData(): array
    {
        $data = $this->model->prepare("SELECT * FROM goods_type");
        $data->execute();
        return $data->fetchAll();
    }
}
