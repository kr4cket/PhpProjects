<?php
namespace App\Views;

class Paginator
{
    private $pageIndex;
    private $pageCount;
    private $link = "";
    private $dots = '...';

    public function __construct($pageCount, $pageIndex, $linkParts)
    {
        $this->pageIndex = $pageIndex;
        $this->pageCount = $pageCount;
        
        foreach ($linkParts as $partKey => $part) {
            $this->link.="&$partKey=$part";
        }
    }

    public function render($pageWidth)
    {
        $pages = $this->createPageList($pageWidth);
        $data = [
            'pages' => $pages,
            'link' => $this->link
        ];
        return (new PrepareHtmlView("pagination", $data))->render();
    }

    private function createPageList($pageWidth) {
        if ($this->pageCount < $pageWidth*2+1){
            $pageArray = [];
            for ($i = 1; $i <= $this->pageCount; $i++) {
                $pageArray[] = $i;
            }
            return $pageArray;
        }

        if ($this->pageCount - $this->pageIndex < 2) {
            return [1, $this->dots, $this->pageCount - 2, $this->pageCount - 1, $this->pageCount];
        }
        if ($this->pageIndex - 1 < 2) {
            return [1, 2, 3, $this->dots, $this->pageCount];
        }

        if ($this->pageIndex - 1 <= 3) {
            return [1, 2, 3, 4, 5, 6, $this->dots, $this->pageCount];
        }

        if ($this->pageCount - $this->pageIndex <= 3) {
            return [1, $this->dots, $this->pageCount - 4, $this->pageCount - 3, $this->pageCount - 2, $this->pageCount - 1, $this->pageCount];
        }

        return [1, $this->dots, $this->pageIndex - 2, $this->pageIndex - 1,
            $this->pageIndex, $this->pageIndex + 1, $this->pageIndex + 2, $this->dots, $this->pageCount];
    }
}

