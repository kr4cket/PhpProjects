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

        if ($this->pageCount - $this->pageIndex < $pageWidth) {
            $pages = [1];
            $pages[] = $this->dots;
            for ($i = $pageWidth; $i >= 0; $i--) {
                $pages[] = $this->pageCount - $i;
            }
            return $pages;
        }

        if ($this->pageCount - $this->pageIndex == $pageWidth) {
            $pages = [1];
            $pages[] = $this->dots;
            for ($i = $pageWidth; $i > 0; $i--) {
                $pages[] = $this->pageIndex - $i;
            }
            for ($i = $pageWidth; $i >= 0; $i--) {
                $pages[] = $this->pageCount - $i;
            }
            return $pages;
        }

        if ($this->pageCount - $this->pageIndex == $pageWidth+1) {
            $pages = [1];
            $pages[] = $this->dots;
            for ($i = $pageWidth; $i >= 0; $i--) {
                $pages[] = $this->pageIndex - $i;
            }
            for ($i = $pageWidth; $i >= 0; $i--) {
                $pages[] = $this->pageCount - $i;
            }
            return $pages;
        }

        
        if ($this->pageIndex - 1 < $pageWidth) {
            $pages = [];
            for ($i = 1; $i <= $pageWidth+1; $i++) {
                $pages[] = $i;
            }
            $pages[] = $this->dots;
            $pages[] = $this->pageCount;
            return $pages;
        }

        if ($this->pageIndex - 1 == $pageWidth) {
            $pages = [];
            for ($i = 1; $i <= $pageWidth*2+1; $i++) {
                $pages[] = $i;
            }
            $pages[] = $this->dots;
            $pages[] = $this->pageCount;
            return $pages;
        }

        if ($this->pageIndex - 1 == $pageWidth + 1) {
            $pages = [];
            for ($i = 1; $i <= $pageWidth*2+2; $i++) {
                $pages[] = $i;
            }
            $pages[] = $this->dots;
            $pages[] = $this->pageCount;
            return $pages;
        }

        $pages = [1,$this->dots];
        for ($i = $pageWidth; $i > 0; $i--) {
            $pages[] = $this->pageIndex - $i;
        }
        for ($i = 0; $i < $pageWidth+1; $i++) {
            $pages[] = $this->pageIndex + $i;
        }
        $pages[] = $this->dots;
        $pages[] = $this->pageCount;
        return $pages;
    }
}

