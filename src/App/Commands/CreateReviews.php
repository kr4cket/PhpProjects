<?php

namespace App\Commands;

use App\Core\ConsoleCommand;
use App\Models\GoodsReviewModel;
use App\Models\GoodsModel;
use Faker;

class CreateReviews extends ConsoleCommand
{
    private $generator;
    private $reviewModel;
    private $goodModel;

    public function __construct()
    {
        $this->generator = Faker\Factory::create('ru_RU');
        $this->reviewModel = new GoodsReviewModel();
        $this->goodModel = new GoodsModel();
    }

    public function execute($number=null): string
    {
        if (isset($number)) {
            if($number > 10000) {
                return "Число превышает предельно допустимую норму!";
            }
            $this->CreateReviewRecords($number);

            return "Сгенерировано ".$number." записей";
        }

        return $this->getInfo();
    }

    private function CreateReviewRecords($count)
    {
        $rowData = [];
        while ($count > 0) {
            $person = explode(' ', $this->generator->name);
            $rowData['good_id'] = $this->goodModel->getRandomId();
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

    public function getInfo(): string
    {
        return "-cr, --create_reviews [REVIEWS_NUM] - Заполняет базу данных отзывов";
    }

}