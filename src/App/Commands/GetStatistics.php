<?php

namespace App\Commands;

use App\Core\ConsoleCommand;
use App\Models\GoodsModel;
use App\Models\GoodsReviewModel;

class GetStatistics extends ConsoleCommand
{
    private $reviewModel;
    private $goodsModel;
    private $commands = [
        '-a'  => "getAllStatistics",
        '-ag' => "getGoodsStatistics", 
        '-ar' => "getReviewsStatistics", 
        '-g'  => "getAllGoodsStatistics", 
        '-mr' => "getModeratedReviewsStatistics", 
        '-r'  => "getAllReviewsStatistics", 
        '-sg' => "getSoldOutGoodsStatistics"
    ];

    public function __construct()
    {
        $this->reviewModel = new GoodsReviewModel();
        $this->goodsModel = new GoodsModel();
    }

    public function execute($args=''): string
    {
        if (array_key_exists($args,$this->commands)) {
            $command = $this->commands[$args];

            return GetStatistics::$command();
        }

        return $this->getInfo();
    }

    public static function getInfo(): string
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

    private function getAllStatistics(): string
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

    private function getAllReviewsStatistics(): string
    {
        $count = $this->reviewModel->getAllReviews();

        return "Всего ".$count." отзывов на товары";
    }

    private function getModeratedReviewsStatistics(): string
    {
        $count = $this->reviewModel->getModeratedReviews();

        return "На рассмотрении ".$count." отзывов";
    }

    private function getReviewsStatistics(): string
    {
        $count = $this->reviewModel->getActiveReviews();

        return "Всего активно ".$count." отзывов";
    }

    private function getAllGoodsStatistics(): string
    {
        $count = $this->goodsModel->getAllGoodsCount();

        return "Всего ".$count." товаров";
    }

    private function getGoodsStatistics(): string
    {
        $count = $this->goodsModel->getGoodsCount();

        return "В наличии ".$count." товаров";
    }

    private function getSoldOutGoodsStatistics(): string
    {
        $count = $this->goodsModel->getSoldOutGoodsCount();
        
        return "Распродано ".$count." товаров";
    }
}