<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use LengthException;

class ShipInSea extends Model
{
    use HasFactory;

    protected $table = 'ships_in_sea';
    protected $fillable = ['player_id', 'ship_id', 'orientation', 'x_coord', 'y_coord'];
    public $timestamps = false;

    private $field = [
        [['empty', 0], ['empty', 0],['empty', 0],['empty', 0],['empty', 0],['empty', 0],['empty', 0],['empty', 0],['empty', 0],['empty', 0]],
        [['empty', 0], ['empty', 0],['empty', 0],['empty', 0],['empty', 0],['empty', 0],['empty', 0],['empty', 0],['empty', 0],['empty', 0]],
        [['empty', 0], ['empty', 0],['empty', 0],['empty', 0],['empty', 0],['empty', 0],['empty', 0],['empty', 0],['empty', 0],['empty', 0]],
        [['empty', 0], ['empty', 0],['empty', 0],['empty', 0],['empty', 0],['empty', 0],['empty', 0],['empty', 0],['empty', 0],['empty', 0]],
        [['empty', 0], ['empty', 0],['empty', 0],['empty', 0],['empty', 0],['empty', 0],['empty', 0],['empty', 0],['empty', 0],['empty', 0]],
        [['empty', 0], ['empty', 0],['empty', 0],['empty', 0],['empty', 0],['empty', 0],['empty', 0],['empty', 0],['empty', 0],['empty', 0]],
        [['empty', 0], ['empty', 0],['empty', 0],['empty', 0],['empty', 0],['empty', 0],['empty', 0],['empty', 0],['empty', 0],['empty', 0]],
        [['empty', 0], ['empty', 0],['empty', 0],['empty', 0],['empty', 0],['empty', 0],['empty', 0],['empty', 0],['empty', 0],['empty', 0]],
        [['empty', 0], ['empty', 0],['empty', 0],['empty', 0],['empty', 0],['empty', 0],['empty', 0],['empty', 0],['empty', 0],['empty', 0]],
        [['empty', 0], ['empty', 0],['empty', 0],['empty', 0],['empty', 0],['empty', 0],['empty', 0],['empty', 0],['empty', 0],['empty', 0]],
    ];

    public function getFields($me, $enemy, Shot $shots)
    {
        $myShips = $this->select('*')->where('player_id', '=', $me)->get();
        $enemyShots = $shots->select('*')->where('player_id', '=', $enemy)->get();

        $enemyShips = $this->select('*')->where('player_id', '=', $enemy)->get();
        $myShots = $shots->select('*')->where('player_id', '=', $me)->get();

        $field['fieldMy'] = $this->getField($myShips, $enemyShots);
        $field['fieldEnemy'] = $this->getField($enemyShips, $myShots);

        return $field;
    }

    public function getMyField($me) 
    {
        $myShips = $this->select('*')->where('player_id', '=', $me)->get();
        $field = $this->getField($myShips, []);
        return $field;
    }

    private function getField($ships, $shots)
    {
        $userField = $this->field;
        foreach ($ships as $ship) {
            $this->createShip($ship, $userField);
        }
        $this->createShots($shots, $userField);

        return $userField;
    }

    private function createShip($ship, &$field)
    {
        $name = Ship::getName($ship['ship_id']);
        $length = $name[0];

        if ($ship['orientation']) {
            $begin = $ship['y_coord'];
            $static = $ship['x_coord'];

            for ($cell = $begin; $cell < $begin + $length; $cell++){
                $field[$static][$cell] = [$name, 0];
            }
            
        } else {

            $begin = $ship['x_coord'];
            $static = $ship['y_coord'];

            for ($cell = $begin; $cell < $begin + $length; $cell++){
                $field[$cell][$static] = [$name, 0];
            }
        }

    }

    private function createShots($shots, &$field)
    {
        foreach ($shots as $shot) {
            $field[$shot['x_coord']][$shot['y_coord']][1] = 1;
        }
    }

    public function clear($playerId)
    {
        $this->where('player_id', '=', $playerId)->delete();
    }

    public function addOneShip($ship, $code)
    {   
        if(empty($ship['ship'])) {
            return "Не переданы обязательные параметры";
        }

        if (empty($ship['x']) && empty($ship['y'])) {
            $this->deleteOneShip($ship, $code);
            return;
        }

        $check = $this->checkPosition($ship, $code);

        if (!empty($check)) {
            return $check;
        }

        $this->updateOrCreate(
        [
            'player_id'     => $code,
            'ship_id'       => $this->getShipIdByName($ship['ship']),
        ],
        [
            'orientation'   => $ship['orientation'] == 'vertical' ? 1 : 0,
            'x_coord'       => $ship['x'],
            'y_coord'       => $ship['y']
        ]);

    }

    public function addAllShips($ships, $code) 
    {
        $error = '';
        foreach($ships as $ship) {
            $error = $this->addOneShip($ship, $code);
            if (!empty($error)) {
                break;
            }
        }
        return $error;
    }

    private function getShipIdByName($name) 
    {
        $ship = Ship::getShipByName($name);

        return $ship['id'];
    }

    private function getPreviousPosition($ship_id, $playerCode)
    {
        $coords = $this->select('x_coord', 'y_coord')->where("player_id", "=", $playerCode)->where("ship_id", "=", $ship_id)->first();
        return $coords;
    }

    public function getPlacedShips($code) 
    {
        $data = [];
        $ships = $this->select('ship_id')->where('player_id', '=', $code)->get();
        foreach ($ships as $ship) {
            $data[] = Ship::getName($ship['ship_id']);
        }
        return $data;
    }

    private function deleteOneShip($shipData, $code)
    {
        $this->where('player_id', '=', $code)->where('ship_id', '=', $this->getShipIdByName($shipData['ship']))->delete();
    }

    private function checkPosition($shipData, $playerCode)
    {
        $length = $shipData['ship'][0];
        $field = $this->getMyField($playerCode);
        $x = $shipData['x'];
        $y = $shipData['y'];

        $begin = $shipData['orientation'] == 'vertical' ? $y : $x;

        if ($begin + $length > 10) {
            return "Выход за границы поля!";
        }

        if ($shipData['orientation'] == 'vertical') {
            for ($cell = $y-1; $cell < $y + $length+1; $cell++) {

                if (
                    (isset($field[$x-1][$cell]) && ($field[$x-1][$cell][0] != 'empty' && $field[$x-1][$cell][0] != $shipData['ship'])) ||
                    (isset($field[$x+1][$cell]) && ($field[$x+1][$cell][0] != 'empty' && $field[$x+1][$cell][0] != $shipData['ship'])) ||
                    (isset($field[$x][$cell])   && ($field[$x][$cell][0]   != 'empty' && $field[$x][$cell][0]   != $shipData['ship']))
                ) {
                    return "Пересечение с другим кораблем";
                }

            }
        } else {

            for ($cell = $x-1; $cell < $x + $length+1; $cell++) {

                if (
                    (isset($field[$cell][$y-1]) && ($field[$cell][$y-1][0] != 'empty' && $field[$cell][$y-1][0] != $shipData['ship'])) ||
                    (isset($field[$cell][$y+1]) && ($field[$cell][$y+1][0] != 'empty' && $field[$cell][$y+1][0] != $shipData['ship'])) ||
                    (isset($field[$cell][$y])   && ($field[$cell][$y][0]   != 'empty' && $field[$cell][$y][0]   != $shipData['ship']))
                ) {
                    return "Пересечение с другим кораблем".'  ';
                }
    
            }
        }

    }

}
