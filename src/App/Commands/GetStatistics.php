<?php

namespace App\Commands;
use App\Core\ConsoleCommand;
use App\Models\GoodsModel;

class GetStatistics extends ConsoleCommand
{
    private $commandOptions = ['-a', '-ag', '-ar', '-g', '-mr', '-r', '-sg'];
    private $reviewModel;
    private $goodsModel;
    public function __construct()
    {
        $this->reviewModel = new reviewModel();
        $this->goodsModel = new GoodsModel();
    }

    public function execute($args)
    {
        
    }
}