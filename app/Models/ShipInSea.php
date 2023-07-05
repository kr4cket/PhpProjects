<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipInSea extends Model
{
    use HasFactory;

    protected $table = 'ships_in_sea';

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
        } else {
            $begin = $ship['x_coord'];
            $static = $ship['y_coord'];
        }

        for ($cell = $begin; $cell < $begin + $length; $cell++){
            $field[$static][$cell] = [$name, 0];
        }
    }

    private function createShots($shots, &$field)
    {
        foreach ($shots as $shot) {
            $field[$shot['x_coord']][$shot['y_coord']][1] = 1;
        }
    }
}
