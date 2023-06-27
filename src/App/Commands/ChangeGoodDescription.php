<?php

namespace App\Commands;
use App\Core\ConsoleCommand;
use App\Models\GoodsModel;

class ChangeGoodDescription extends ConsoleCommand
{
    private $goodModel;

    public function __construct()
    {
        $this->goodModel = new GoodsModel;
    }

    public function execute($id = 0, $decription = '')
    {
        print_r($decription);
        if (!empty($id)) {
            return implode(PHP_EOL, $this->goodModel->changeDescription($id, $decription));
        }
        return $this->getInfo();
    }

    public function getInfo()
    {
        return "-cgd, --create_good_description [ID] [TEXT] - Изменяет описание конкретного товара";
    }
}