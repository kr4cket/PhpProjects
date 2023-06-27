<?php

namespace App\Commands;
use App\Core\ConsoleCommand;
use Faker;
use App\Models\GoodsModel;

class CreateGoods extends ConsoleCommand
{
    private $generator;
    private $goodModel;

    public function __construct()
    {
        $this->generator = Faker\Factory::create('ru_RU');
        $this->goodModel = new GoodsModel();
    }

    public function execute($number=null)
    {
        if (isset($number)) {

            if($number > 10000) {
                return "Число превышает предельно допустимую норму!";
            }
            $this->CreateGoodsRecords($number);
            return "Сгенерировано ".$number." записей";
        }
        return $this->getInfo();
    }


    private function CreateGoodsRecords($count)
    {
        $rowData = [];
        while ($count > 0) {
            $rowData['id'] = null;
            $rowData['goodName'] = rtrim($this->generator->text(10),'.');
            $rowData['typeList'] = mt_rand(1,5);
            $rowData['manufactureList'] = mt_rand(1,8);
            $rowData['goodCost'] = mt_rand(5000, 200000);
            $rowData['goodDescription'] = $this->generator->realText(400);
            $rowData['isSoldOut'] = mt_rand(0,1);
            $count -= 1;
            $this->goodModel->addGoodData($rowData);
        }
    }


    public function getInfo()
    {
        return "-cg, --create_goods [GOODS_NUM] - Заполняет базу данных товаров";
    }

}