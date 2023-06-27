<?php

namespace App\Commands;
use App\Core\ConsoleCommand;
use App\Models\GoodsModel;
use App\Models\GoodsReviewModel;

class GetStatistics extends ConsoleCommand
{
    private $commands = [
        '-a'  => "getAllStatistics",
        '-ag' => "getGoodsStatistics", 
        '-ar' => "getReviewsStatistics", 
        '-g'  => "getAllGoodsStatistics", 
        '-mr' => "getModeratedReviewsStatistics", 
        '-r'  => "getAllReviewsStatistics", 
        '-sg' => "getSoldOutGoodsStatistics"
    ];
    private $reviewModel;
    private $goodsModel;

    public function __construct()
    {
        $this->reviewModel = new GoodsReviewModel();
        $this->goodsModel = new GoodsModel();
    }

    public function execute($args='')
    {
        if (array_key_exists($args,$this->commands)) {
            $command = $this->commands[$args];
            return GetStatistics::$command();
        }
        return $this->getInfo();
    }

    public function getInfo()
    {
        return "-gs, --get_statistics [OPTION] - Получить информацию о сайте".
        PHP_EOL."OPTIONS".PHP_EOL."-a  - Получить всю информацию".
        PHP_EOL."-ag - Получить количество не распроданных товаров".
        PHP_EOL."-ar - Получить количество активных отзывов".
        PHP_EOL."-g  - Получить количество всех товаров".
        PHP_EOL."-mr - Получить количество отзывов на модерации".
        PHP_EOL."-r  - Получить количество всех отзывов".
        PHP_EOL."-sg - Получить количество распроданных товаров";
    }

    private function getAllStatistics()
    {
        $statistics = ["Вся статистика по сайту:"];
        foreach ($this->commands as $command) {
            if($command == "getAllStatistics") {
                continue;
            }
            $statistics[] = GetStatistics::$command();
        }
        return implode(PHP_EOL, $statistics);
    }

    private function getAllReviewsStatistics()
    {
        $count = $this->reviewModel->getAllReviews();
        return "Всего ".$count." отзывов на товары";
    }

    private function getModeratedReviewsStatistics()
    {
        $count = $this->reviewModel->getModeratedReviews();
        return "На рассмотрении ".$count." отзывов";
    }

    private function getReviewsStatistics()
    {
        $count = $this->reviewModel->getActiveReviews();
        return "Всего активно ".$count." отзывов";
    }

    private function getAllGoodsStatistics()
    {
        $count = $this->goodsModel->getAllGoodsCount();
        return "Всего ".$count." товаров";
    }

    private function getGoodsStatistics()
    {
        $count = $this->goodsModel->getGoodsCount();
        return "В наличии ".$count." товаров";
    }

    private function getSoldOutGoodsStatistics()
    {
        $count = $this->goodsModel->getSoldOutGoodsCount();
        return "Распродано ".$count." товаров";
    }
}