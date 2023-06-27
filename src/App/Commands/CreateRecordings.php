<?php

namespace App\Commands;
use App\Core\ConsoleCommand;
use App\Models\GoodsReviewModel;
use Faker;
use App\Models\GoodsModel;

class CreateRecordings extends ConsoleCommand
{
    private $data;
    private $generator;
    private $goodModel;
    private $reviewModel;

    public function __construct()
    {
        $this->generator = Faker\Factory::create('ru_RU');
        $this->goodModel = new GoodsModel();
        $this->reviewModel = new GoodsReviewModel();
    }

    public function execute($args)
    {
        if($args[1] > 10000) {
         return "Число превышает предельно допустимую норму!";
        }

        if ($args[0] == '-r') {
            $this->CreateReviewRecords($args[1]);
            return "Сгенерировано ".$args[1]." записей";
        }
        if ($args[0] == '-g') {
            $this->CreateGoodsRecords($args[1]);
            return "Сгенерировано ".$args[1]." записей";
        }

        return ConsoleCommand::getCommands();
    }

    private function CreateReviewRecords($count)
    {
        $rowData = [];
        while ($count > 0) {
            $person = explode(' ', $this->generator->name);
            $rowData['good_id'] = $this->getId();
            $rowData['name'] = $person[1];
            $rowData['surname'] = $person[0];
            $rowData['phoneNumber'] = $this->generator->phoneNumber;
            $rowData['review'] = $this->generator->realText(350);
            $rowData['rating'] = mt_rand(1,5);
            $rowData['is_active'] = mt_rand(0,1);
            $count -= 1;
            $this->reviewModel->addReview($rowData);
        }
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

    private function getId()
    {
        $id = mt_rand(1, $this->goodModel->getElementsCount());
        while ($this->goodModel->checkId($id)) {
            $id = mt_rand(1, $this->goodModel->getElementsCount());
        }
        return $id;
    }

}