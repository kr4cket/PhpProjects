<?php
namespace App\Views;

class Paginator
{
    private $pageIndex;
    private $pageCount;
    private $orderType;

    public function __construct($pageCount, $pageIndex, $orderType)
    {
        $this->pageIndex = $pageIndex;
        $this->pageCount = $pageCount;
        $this->orderType = $orderType;
        
        if ($orderType != 'id') {
            $this->orderType="&orderType=$orderType";
        } else {
            $this->orderType = '';
        }
    }

    public function render()
    {
        $pages = $this->createPageList();
        $content = "";
        foreach ($pages as $page) {
            if ($page == '...') {
                continue;
            }
            $content.= "<a href="."/?page=".$this->pageIndex.$this->orderType."><?=$this->pageIndex?></a>";
        }
        return $content;
    }

    private function createPageList() {
        if ($this->pageCount < 5){
            $pageArray = [];
            for ($i = 1; $i <= $this->pageCount; $i++) {
                $pageArray[] = $i;
            }
            return $pageArray;
        }
        if ($this->pageIndex - 2 > 1 && $this->pageIndex+2 < $this->pageCount) {
            return [1, '...', $this->pageIndex - 2, $this->pageIndex - 1, $this->pageIndex, 
            $this->pageIndex + 1, $this->pageIndex + 2, '...', $this->pageCount];
        }
        if ($this->pageIndex - 2 > 1 && $this->pageIndex+2 < $this->pageCount) {
            return [1, '...', $this->pageCount - 2, $this->pageCount - 1, $this->pageCount];
        }
        if ($this->pageIndex + 2 < $this->pageCount) {
            return [1, 2, 3, '...', $this->pageCount];
        }
    }
}

