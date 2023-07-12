<?php

namespace App\Services;
class GameService
{

    public function generateShips(): array
    {

        $orientation = ['vertical', 'horizontal'];
        $ships = ['4-1', '3-1', '3-2', '2-1', '2-2', '2-3', '1-1', '1-2', '1-3','1-4'];
        $field = array_fill(0, 10, array_fill(0,10, 'check'));
        $result = [];

        foreach ($ships as $ship) {

            $isLooped = true;
            $length = $ship[0];

            while ($isLooped) {

                $x = rand(0,9);
                $y = rand(0,9);
                $posOrient = $orientation[array_rand($orientation)];

                if ($this->checkProperties($x, $y, $length, $posOrient, $field)) {
                    $this->fillField($field, $x, $y, $length, $posOrient);
                    $isLooped = false;
                    $result[] = [
                        'ship'          => $ship,
                        'x'             => $x,
                        'y'             => $y,
                        'orientation'   => $posOrient
                    ];
                }
            }
        }


        return $result;


    }

    public function fillField(&$field, $x, $y, $length, $posOrient)
    {
        $begin = $posOrient == 'vertical' ? $y : $x;

        for ($cell = 0; $cell < $length; $cell++ ){

            if ($posOrient == 'vertical') {
                $localX = $x;
                $localY = $y + $cell;
            } else {
                $localX = $x + $cell;
                $localY = $y;
            }

            $field[$localX][$localY] = 1;
        }
    }

    public function checkProperties($x, $y, $length, $position, $field)
    {
        $begin = $position == 'vertical' ? $y : $x;

        if ($begin < 0 || $begin + $length > 10) {
            return false;
        }

        if($position == 'vertical') {
            $begin  = $y;
            $edges = $x;
        } else {
            $begin  = $x;
            $edges =  $y;
        }

        for ($edge = $edges - 1; $edge < $edges+2; $edge++) {

            for ($cell = $begin - 1; $cell < $length + $begin+1; $cell++) {

                if($position == 'vertical') {
                    $y = $cell;
                    $x = $edge;
                } else {
                    $x = $cell;
                    $y = $edge;
                }

                if (isset($field[$x][$y]) && ($field[$x][$y] == 1)) {
                    return false;
                }
            }

        }

        return true;
    }
}

