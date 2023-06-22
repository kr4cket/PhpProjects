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
        'orderByPrice' => 'price'
    ];
    public const PAGE_SIZE = 5;

    public function __construct()
    {
        parent::__construct();
        $this->type = new GoodsTypeModel();
        $this->manufacture = new GoodsManufactureModel();
    }

    public function getPage($page=1, $orderType='default')
    {
        $limit = static::PAGE_SIZE;
        $page = ($page-1)*$limit;
        $order = $this->getOrderType($orderType);
        //print_r($order);
        $pageData = $this->model->prepare("SELECT * FROM goods LIMIT :page, :limit");
        $pageData->execute(['page'=>$page, 'limit'=>$limit]);
        return $pageData->fetchAll();
    }

    private function getOrderType($type)
    {
        if (array_key_exists($type, $this->orderTypes)) {
            return $this->orderTypes[$type];
        }
        return 'id';
    }

    public function getGoodData($id)
    {
        $goodData = $this->model->prepare("SELECT * FROM goods WHERE id=:id");
        $goodData->execute(['id'=> $id]);
        $goodData = $this->modifyData($goodData->fetch());
        return $goodData;
    }

    public function addGoodData($data) {
        $goodData = $this->model->prepare("INSERT INTO goods (id, name, type_id, manufacture_id, price, description, is_sold_out) 
        VALUES (:id ,:name, :type_id, :manufacture_id, :price, :description, :is_sold_out);");
        $goodData->execute([
            'id' => null,
            'name' => $data['goodName'],
            'type_id' => $data['typeList'],
            'manufacture_id' => $data['manufactureList'],
            'price' => $data['goodCost'],
            'description' => $data['goodDescription'],
            'is_sold_out' => 0
        ]);
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

    public function isValid($validateData) 
    {
        foreach ($validateData as $type => $param) {
            if (array_key_exists($type, $this->paramRules)) {
                $this->validator->validate($this->paramRules[$type], $param);
            }
        }
        return empty($this->validator->getErrors());
    }

    public function getEmptyFormData()
    {
        return [
            'goodName' => '',
            'goodCost' => '',
            'goodDescription' => '',
            'typeList' => $this->type->getData(),
            'manufactureList' => $this->manufacture->getData(),
            'errors' => []
        ];
    }
    public function getFormData($postData)
    {
        return [
            'goodName' => $postData['goodName'],
            'goodCost' => $postData['goodCost'],
            'goodDescription' => $postData['goodDescription'],
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
        $elementsNumber = $this->getElementsNumber();
        return (static::PAGE_SIZE * $pageNumber < $elementsNumber);
    }

    private function getElementsNumber()
    {
        $number = $this->model->query("SELECT COUNT(*) FROM goods");
        return $number->fetch()['COUNT(*)'];
    }
}

?>