<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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


    public function getMyField($player) 
    {
        $myShips = $player->ships;
        $field = $this->getField($myShips, [], false);

        return $field;
    }

    public function getField($ships, $shots, $isEnemy)
    {
        $userField = $this->field;
        $this->createShots($shots, $userField, $isEnemy);

        foreach ($ships as $ship) {
            $this->createShip($ship, $userField, $isEnemy);
        }

        return $userField;
    }

    public function fieldCheckShot($ship, $shots)
    {
        $length = Ship::getName($ship['ship_id'])[0];

        if ($ship['orientation']) {

            $begin  = $ship['y_coord'];

            for ($y = $begin; $y < $begin + $length; $y++) {
                if ($y == $shots['y'] && $ship['x_coord'] == $shots['x']) {
                    return true;
                }
            }

        } else {

            $begin = $ship['x_coord'];

            for ($x = $begin; $x < $begin + $length; $x++) {
                if ($x == $shots['x'] && $ship['y_coord'] == $shots['y']) {
                    return true;
                }
            }

        }

        return false;
    }

    private function createShip($ship, &$field, $isEnemy)
    {
        $name = Ship::getName($ship['ship_id']);
        $length = $name[0];
        $integrity = $length;

        if ($ship['orientation']) {
            
            $begin  = $ship['y_coord'];
            $static = $ship['x_coord'];

            for ($cell = $begin; $cell < $begin + $length; $cell++) {

                if ($field[$static][$cell][1] == 1) {
                    $integrity -= 1;
                    $field[$static][$cell][0] = $name;
                } else {
                    $field[$static][$cell][0] = $isEnemy ? 'hidden' : $name;
                }

            }

            if ($integrity <= 0) {
                $this->createEdges($field, $ship, $length);
            }

        } else {

            $begin = $ship['x_coord'];
            $static = $ship['y_coord'];


            for ($cell = $begin; $cell < $begin + $length; $cell++) {

                if ($field[$cell][$static][1] == 1) {
                    $integrity -= 1;
                    $field[$cell][$static][0] = $name;
                } else {
                    $field[$cell][$static][0] = $isEnemy ? 'hidden' : $name;
                }

            }

            if ($integrity <= 0) {
                $this->createEdges($field, $ship, $length);
            }

        }

    }

    private function createEdges(&$field, $ship, $length) {

        if($ship['orientation']) {
            $begin  = $ship['y_coord'];
            $static = $ship['x_coord'];

            for ($cell = $begin-1; $cell < $begin+$length+1; $cell++) {

                if (isset($field[$static][$cell]) && ($cell == $begin-1 || $cell == $begin + $length)) {
                    $field[$static][$cell][1] = 1;

                    // $this->user->shots()->create([
                    //     'player_id' => $this->user->id,
                    //     'x_coord'   => $static,
                    //     'y_coord'   => $cell
                    // ]);
                }

                if (isset($field[$static-1][$cell])) {
                    $field[$static-1][$cell][1] = 1;

                    // $this->user->shots()->create([
                    //     'player_id' => $this->user->id,
                    //     'x_coord'   => $static-1,
                    //     'y_coord'   => $cell
                    // ]);
                }

                if (isset($field[$static+1][$cell])) {
                    $field[$static+1][$cell][1] = 1;

                    // $this->user->shots()->create([
                    //     'player_id' => $this->user->id,
                    //     'x_coord'   => $static+1,
                    //     'y_coord'   => $cell
                    // ]);
                }
            }

        } else {
            $begin = $ship['x_coord'];
            $static = $ship['y_coord'];

            for ($cell = $begin-1; $cell < $begin+$length+1; $cell++) {

                if (isset($field[$cell][$static]) && ($cell == $begin-1 || $cell == $begin + $length)) {
                    $field[$cell][$static][1] = 1;

                    // $this->user->shots()->create([
                    //     'player_id' => $this->user->id,
                    //     'x_coord'   => $cell,
                    //     'y_coord'   => $static
                    // ]);
                }

                if (isset($field[$cell][$static-1])) {
                    $field[$cell][$static-1][1] = 1;

                    // $this->user->shots()->create([
                    //         'player_id' => $this->user->id,
                    //         'x_coord'   => $cell,
                    //         'y_coord'   => $static-1
                    // ]);
                }

                if (isset($field[$cell][$static+1])) {
                    $field[$cell][$static+1][1] = 1;

                    // $this->user->shots()->create([
                    //     'player_id' => $this->user->id,
                    //     'x_coord'   => $cell,
                    //     'y_coord'   => $static+1
                    // ]);
                }
            }
        }



    }

    public function player()
    {
        return $this->belongsTo(Player::class, 'player_id', 'id');
    }

    private function createShots($shots, &$field)
    {
        foreach ($shots as $shot) {
            $field[$shot['x_coord']][$shot['y_coord']][1] = 1;
        }
    }

    public function clear($player)
    {
        $player->ships->each(fn ($ship) => $ship->delete());
    }

    public function addOneShip($ship, $player)
    {   
        if(empty($ship['ship'])) {
            return "Не переданы обязательные параметры";
        }

        if (empty($ship['x']) && empty($ship['y'])) {
            $this->deleteOneShip($ship, $player->id);
            return;
        }

        $check = $this->checkPosition($ship, $player);

        if (!empty($check)) {
            return $check;
        }

        $this->updateOrCreate(
        [
            'player_id'     => $player->id,
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

    private function checkPosition($shipData, $player)
    {
        $length = $shipData['ship'][0];
        $field = $this->getMyField($player);
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

                    return "Пересечение с другим кораблем";
                }
    
            }
        }

    }

}
