<?php

namespace App\Models;

use \App\Models\AdditionModel;
use \App\Models\GoodsTypeModel;
use \App\Models\GoodsManufactureModel;

class GoodsModel extends AdditionModel
{
    private $type;
    private $manufacture;
    private $user;
    private $userId;
    const PAGE_SIZE = 20;
    private $paramRules = [
        'goodName'          => ['isEmpty', 'minLength'],
        'typeList'          => ['isChecked'],
        'manufactureList'   => ['isChecked'],
        'goodCost'          => ['onlyDigits', 'isEmpty', 'minLength', 'isPositiveNumber']
    ];
    private $orderTypes = [
        ''                      => 'id',
        'orderByName'           => 'name',
        'orderByPriceDownToUp'  => 'price',
        'orderByPriceUpToDown'  => 'price DESC',
        'orderByReviews'        => 'reviews'
    ];

    public function __construct()
    {
        parent::__construct();
        $this->type = new GoodsTypeModel();
        $this->manufacture = new GoodsManufactureModel();
        $this->user = new UserModel();
        $this->userId = $_SESSION['id'] ?? null;
    }

    public function checkId($id): bool
    {
        $condition = $this->model->prepare("SELECT * FROM goods WHERE id=:id");
        $condition->execute(['id'=>$id]);

        return empty($condition->fetchAll());
    }

    public function getRandomId(): int
    {
        $id = $this->model->query("SELECT id FROM goods ORDER BY RAND() LIMIT 1");

        return $id->fetch()['id'];
    }

    public function getSoldOutGoodsCount(): int
    {
        $count = $this->model->query("SELECT COUNT(*) FROM goods WHERE is_sold_out = 1");

        return $count->fetch()['COUNT(*)'];
    }

    public function getGoodsCount(): int
    {
        $count = $this->model->query("SELECT COUNT(*) FROM goods WHERE is_sold_out = 0");

        return $count->fetch()['COUNT(*)'];
    }

    public function changeDescription($id, $description): bool
    {
        if ($this->existProductId($id)) {
            $request = $this->model->prepare("UPDATE goods SET description=:description WHERE id=:id");
            $request->execute(['id'=>$id, 'description'=>$description]);
            return true;
        }
        return false;
    }

    public function getPage($page = 1, $orderType = 'default', $filters = []): array
    {
        $limit = static::PAGE_SIZE;
        $startPage = ($page - 1) * $limit;
        $params = [
            'page'      => $startPage,
            'limit'     => $limit,
            'orderType' => $this->getOrderType($orderType),
            'filters'   => $filters
        ];
        $data = $this->getPageData($params);
        $pageData = [
            'goods'         => $data['goods'], 
            'pageCount'     => $data['page'], 
            'currentPage'   => $page, 
            'link'          => $this->getLinkParams($orderType, $filters),
            'manufactures'  => $this->manufacture->getData()
        ];
        return $pageData;
    }

    public function getData($id)
    {
        $goodData = $this->model->prepare("SELECT * FROM goods WHERE id=:id");
        $goodData->execute(['id' => $id]);
        $goodData = $this->modifyData($goodData->fetch());

        return $goodData;
    }

    public function add($data)
    {
        $goodData = $this->model->prepare("INSERT INTO goods (id, name, type_id, manufacture_id, price, description, is_sold_out)
        VALUES (:id ,:name, :type_id, :manufacture_id, :price, :description, :is_sold_out);");
        $goodData->execute([
            'id'                => null,
            'name'              => $data['goodName'],
            'type_id'           => $data['typeList'],
            'manufacture_id'    => $data['manufactureList'],
            'price'             => $data['goodCost'],
            'description'       => $data['goodDescription'],
            'is_sold_out'       => $data['isSoldOut'] ?? 0
        ]);
    }

    public function isValid($validateData): bool
    {
        foreach ($validateData as $type => $param) {
            if (array_key_exists($type, $this->paramRules)) {
                $this->validator->validate($this->paramRules[$type], $param);
            }
        }

        return empty($this->validator->getErrors());
    }

    public function getFormData($postData=null): array
    {
        return [
            'goodName'          => $postData['goodName'] ?? '',
            'goodCost'          => $postData['goodCost'] ?? '',
            'goodDescription'   => $postData['goodDescription'] ?? '',
            'typeList'          => $this->type->getData(),
            'manufactureList'   => $this->manufacture->getData(),
            'errors'            => $this->validator->getErrors() ?? ''
        ];
    }

    public function existProductId($id): bool
    {
        $checkData = $this->model->prepare("SELECT id FROM goods WHERE id=:id");
        $checkData->execute(['id' => $id]);

        return !empty($checkData->fetchAll());
    }

    public function existPage($pageNumber): bool
    {
        $elementsNumber = $this->getAllGoodsCount();

        return (static::PAGE_SIZE * $pageNumber < $elementsNumber + static::PAGE_SIZE);
    }

    public function getAllGoodsCount(): int
    {
        $number = $this->model->query("SELECT COUNT(*) FROM goods");

        return $number->fetch()['COUNT(*)'];
    }

    private function getPageCount($request, $params): int
    {
        $count = $this->model->prepare("SELECT COUNT(*) 
            FROM goods $request");
        $count->execute($params);

        return ceil($count->fetch()['COUNT(*)']/static::PAGE_SIZE);
    }

    private function getLinkParams($orderType="", $filters=[]): array
    {
        $linkData = [];
        $linkData['orderType'] = $orderType;
        foreach ($filters as $filter => $type) {
            if($type == "default") {
                continue;
            }
            $linkData[$filter] = htmlspecialchars($type);
        }
        
        return $linkData;
    }

    private function modifyData($data): array
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

    private function getOrderType($type): string
    {
        return $this->orderTypes[$type] ?? 'id';
    }
    
    private function getPageData($params=[]): array 
    {   
        $requestParams = [
            'page'  => $params['page'],
            'limit' => $params['limit']
        ];
        $filterParams = $this->makeFilterParams($params['filters']);
        $filterRequest = $filterParams['request'];
        $filterRequestParams = $filterParams['params'];

        if (!empty($filterRequestParams)) {
            $requestParams = [...$requestParams, ...$filterRequestParams];
        }

        if($params['orderType'] != 'reviews') {
            $pageData = $this->model->prepare("SELECT * 
            FROM goods
            $filterRequest 
            ORDER BY ".$params['orderType']."
            LIMIT :page, :limit");
        } else {
            $pageData = $this->model->prepare("SELECT goods.*, 
            (SELECT COUNT(goods_review.id) 
            FROM goods_review
            WHERE goods_review.goods_id = goods.id) AS RATING 
            FROM goods 
            $filterRequest 
            ORDER BY RATING DESC LIMIT :page, :limit");
        }

        $pageData->execute($requestParams);
        $data['goods'] = $pageData->fetchAll();
        $data['page'] = $this->getPageCount($filterRequest,$filterRequestParams);

        return $data;
    }

    private function makeFilterParams($filterParams): array
    {
        $result = [];
        $params = [];

        foreach ($filterParams as $filter=>$param) {
            if (empty($param)) {
                continue;
            }
            $result[] = match($filter) {
                "manufacture"       => "manufacture_id=:manufacture",
                "minPrice"          => "price > :minPrice",
                "maxPrice"          => "price < :maxPrice",
                "goodFilterName"    => "name LIKE CONCAT('%', :goodFilterName, '%')"
            };
            $params[$filter] = $param; 
        }
        
        if (!$this->user->isAdmin($this->userId)) {
            $result[] = "is_sold_out = 0";
        } 

        $result = implode(' AND ', $result);
        
        if (strlen($result) > 0) {
            $result = "WHERE ".$result;
        }
        
        $resultArray = [
            'request'   => $result,
            'params'    => $params
        ];

        return $resultArray;
    }
}
