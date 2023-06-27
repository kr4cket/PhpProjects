<?php

namespace App\Models;

use \App\Core\Model;
use App\Core\Validator;
use \App\Models\GoodsReviewModel;
use \App\Models\GoodsTypeModel;
use \App\Models\GoodsManufactureModel;

class GoodsModel extends Model
{
    private $type;
    private $manufacture;
    private $paramRules = [
        'goodName' => ['isEmpty', 'minLength'],
        'typeList' => ['isChecked'],
        'manufactureList' => ['isChecked'],
        'goodCost' => ['onlyDigits', 'isEmpty', 'minLength', 'isPositiveNumber']
    ];
    private $orderTypes = [
        'default' => 'id',
        'orderByName' => 'name',
        'orderByPriceDownToUp' => 'price',
        'orderByPriceUpToDown' => 'price DESC',
        'orderByReviews' => 'reviews'
    ];

    const PAGE_SIZE = 5;

    public function __construct()
    {
        parent::__construct();
        $this->type = new GoodsTypeModel();
        $this->manufacture = new GoodsManufactureModel();
    }

    public function checkId($id)
    {
        $condition = $this->model->prepare("SELECT * FROM goods WHERE id=:id");
        $condition->execute(['id'=>$id]);
        return empty($condition->fetchAll());
    }

    public function getPage($page = 1, $orderType = 'default', $filters ="default")
    {
        $limit = static::PAGE_SIZE;
        $startPage = ($page - 1) * $limit;
        $order = $this->getOrderType($orderType);
        if($order != 'reviews') {
            $pageData = $this->model->prepare("SELECT * 
            FROM goods 
            ORDER BY $order 
            LIMIT :page, :limit");
        } else {
            $pageData = $this->model->prepare("SELECT goods.*, 
            (SELECT COUNT(goods_review.id) 
            FROM goods_review
            WHERE goods_review.goods_id = goods.id) AS RATING 
            FROM goods 
            ORDER BY RATING DESC LIMIT :page, :limit");
        }
        $pageData->execute(['page' => $startPage, 'limit' => $limit]);
        $pageData = [
            'goods' => $pageData->fetchAll(), 
            'pageCount' => $this->getPageCount(), 
            'currentPage' =>$page, 
            'link' => $this->getLinkParams($orderType)
        ];
        return $pageData;
    }

    private function getOrderType($type)
    {
        return $this->orderTypes[$type] ?? 'id';
    }

    public function getGoodData($id)
    {
        $goodData = $this->model->prepare("SELECT * FROM goods WHERE id=:id");
        $goodData->execute(['id' => $id]);
        $goodData = $this->modifyData($goodData->fetch());
        return $goodData;
    }

    public function addGoodData($data)
    {
        $goodData = $this->model->prepare("INSERT INTO goods (id, name, type_id, manufacture_id, price, description, is_sold_out)
        VALUES (:id ,:name, :type_id, :manufacture_id, :price, :description, :is_sold_out);");
        $goodData->execute([
            'id' => null,
            'name' => $data['goodName'],
            'type_id' => $data['typeList'],
            'manufacture_id' => $data['manufactureList'],
            'price' => $data['goodCost'],
            'description' => $data['goodDescription'],
            'is_sold_out' => $data['isSoldOut'] ?? 0
        ]);
    }

    private function modifyData($data)
    {
        if ($data) {
            $data['type'] = $this->type->getTypeById($data['type_id']);
            $data['manufacture'] = $this->manufacture->getManufactureById($data['manufacture_id']);
            unset($data['manufacture_id'],  $data['type_id']);
        } else {
            $data = [];
        }
        return $data;
    }

    public function isValid($validateData)
    {
        foreach ($validateData as $type => $param) {
            if (array_key_exists($type, $this->paramRules)) {
                $this->validator->validate($this->paramRules[$type], $param);
            }
        }
        return empty($this->validator->getErrors());
    }

    public function getFormData($postData=null)
    {
        if (!$postData) {
            return [
                'goodName' => '',
                'goodCost' => '',
                'goodDescription' => '',
                'typeList' => $this->type->getData(),
                'manufactureList' => $this->manufacture->getData(),
                'errors' => []
            ];
        }
        return [
            'goodName' => $postData['goodName'] ?? '',
            'goodCost' => $postData['goodCost'] ?? '',
            'goodDescription' => $postData['goodDescription'] ?? '',
            'typeList' => $this->type->getData(),
            'manufactureList' => $this->manufacture->getData(),
            'errors' => $this->validator->getErrors()
        ];
    }

    public function existProductId($id)
    {
        $checkData = $this->model->prepare("SELECT id FROM goods WHERE id=:id");
        $checkData->execute(['id' => $id]);
        return !empty($checkData->fetchAll());
    }
    public function existPage($pageNumber)
    {
        $elementsNumber = $this->getElementsCount();
        return (static::PAGE_SIZE * $pageNumber < $elementsNumber + static::PAGE_SIZE);
    }

    public function getElementsCount()
    {
        $number = $this->model->query("SELECT COUNT(*) FROM goods");
        return $number->fetch()['COUNT(*)'];
    }
    private function getPageCount()
    {
        return ceil($this->getElementsCount()/static::PAGE_SIZE);
    }
    private function getLinkParams($orderType="", $filters=[]) 
    {
        $linkData = [];
        $linkData['orderType'] = $orderType;
        foreach ($filters as $filter => $type) {
            $linkData[$filter] = $type;
        }
        return $linkData;
    }
}
