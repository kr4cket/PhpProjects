<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ShipInSea
 *
 * @property int $id
 * @property string $player_id
 * @property int $ship_id
 * @property string $orientation
 * @property int $x_coord
 * @property int $y_coord
 * @property-read \App\Models\Player $player
 * @method static \Illuminate\Database\Eloquent\Builder|ShipInSea newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShipInSea newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShipInSea query()
 * @method static \Illuminate\Database\Eloquent\Builder|ShipInSea whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShipInSea whereOrientation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShipInSea wherePlayerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShipInSea whereShipId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShipInSea whereXCoord($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShipInSea whereYCoord($value)
 * @property-read \App\Models\Ship $ship
 * @mixin \Eloquent
 */
class ShipInSea extends Model
{
    use HasFactory;

    protected $table = 'ships_in_sea';
    protected $fillable = ['player_id', 'ship_id', 'orientation', 'x_coord', 'y_coord'];
    public $timestamps = false;
    const VERTICAL = '1';

    public function player()
    {
        return $this->belongsTo(Player::class, 'player_id', 'id');
    }
    public function ship()
    {
        return $this->belongsTo(Ship::class, 'ship_id');
    }
    private function getEmptyField()
    {
        return array_fill(0, 10, array_fill(0,10, ['empty', 0]));
    }
    public function getMyField($player)
    {
        $myShips = $player->ships;
        $field = $this->getField($myShips, [], false);

        return $field;
    }

    /**
     * Наполняет поле игрока имеющимимся данными
     * если данных для наполнения нет -> возвращает пустое поле
     *
     * @param Collection $ships
     * @param $playerShots
     * @param bool $isEnemy
     * @return array
     */
    public function filledField(Collection $ships, $playerShots, bool $isEnemy)
    {

        $userField = $this->getEmptyField();
        $this->placeShots($playerShots, $userField, $isEnemy);


        foreach ($ships as $ship) {
            $this->placeShip($ship, $userField, $isEnemy);
        }
        return $userField;
    }

    public function getField(Collection $ships, $playerShots, bool $isEnemy)
    {
        return $this->filledField($ships, $playerShots, $isEnemy);
    }

    /**
     * Проверка на попадаение по кораблю
     * Попал -> true
     * Не попал -> false
     *
     * @param ShipInSea $ship
     * @param array $shots
     * @return bool
     */
    public function fieldCheckShot(ShipInSea $ship, array $shots)
    {

        $length = $ship->ship->getLength();

        if ($ship->orientation == self::VERTICAL) {

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

    /**
     * Размещает корабль на игровом поле
     * Если на всех клетках корабля есть выстрелы (корабль уничтожен) -> создает границы разрушенного корабля
     *
     * @param ShipInSea $ship
     * @param $field
     * @param $isEnemy
     * @return void
     */
    private function placeShip(ShipInSea $ship, &$field, $isEnemy)
    {

        $name = $ship->ship->name;
        $length = $ship->ship->getLength();
        $integrity = $length;

        $x = $ship['x_coord'];
        $y = $ship['y_coord'];

        $begin = $ship->orientation == self::VERTICAL ? $y : $x;

        for ($cell = $begin; $cell < $begin + $length; $cell++) {

            if ($ship->orientation == self::VERTICAL) {
                $y = $cell;
            } else {
                $x = $cell;
            }

            if ($field[$x][$y][1] == 1) {
                $integrity -= 1;
                $field[$x][$y][0] = $name;
            } else {
                $field[$x][$y][0] = $isEnemy ? 'hidden' : $name;
            }

        }

        if ($integrity <= 0) {
            $this->createEdges($field, $ship, $length);
        }

    }

    /**
     * Заполняет поля вокруг разрушенного корабля
     *
     * @param $field
     * @param $ship
     * @param $length
     * @return void
     */
    private function createEdges(&$field, $ship, $length) {

        $enemy = $ship->player->game->getEnemy($ship->player);

        if($ship->orientation == self::VERTICAL) {
            $begin  = $ship->y_coord;
            $edges = $ship->x_coord;
        } else {
            $begin = $ship->x_coord;
            $edges = $ship->y_coord;
        }

        for ($edge = $edges - 1; $edge < $edges+2; $edge++) {

            for ($cell = $begin - 1; $cell < $length+$begin+1; $cell++) {

                if($ship->orientation == self::VERTICAL) {
                    $y = $cell;
                    $x = $edge;
                } else {
                    $x = $cell;
                    $y = $edge;
                }

                if (isset($field[$x][$y]) && $field[$x][$y][1] == 0) {
                    $field[$x][$y][1] = 1;
                    $this->addShot($x, $y, $enemy);
                }
            }

        }
    }

    private function addShot($x, $y, $player)
    {
        $player->shots()->create([
            'player_id' => $player->id,
            'x_coord'   => $x,
            'y_coord'   => $y
        ]);
    }

    private function placeShots($shots, &$field)
    {
        foreach ($shots as $shot) {
            $field[$shot['x_coord']][$shot['y_coord']][1] = 1;
        }
    }


    public function clear($player)
    {
        $player->ships->each(fn ($ship) => $ship->delete());
    }

    /**
     * Добавляет информацию о корабле в базу данных
     *
     * @param array $ship
     * @param Player $player
     * @return string|void
     */
    public function addOneShip(array $ship, Player $player)
    {

        if(empty($ship['ship'])) {
            return "Не переданы обязательные параметры";
        }

        if (!isset($ship['x']) && !isset($ship['y'])) {
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

    public function addAllShips(array $ships, Player $player)
    {
        $error = '';
        foreach($ships as $ship) {
            $error = $this->addOneShip($ship, $player);
            if (!empty($error)) {
                break;
            }
        }
        return $error;
    }

    public function getCoords()
    {
        $length = $this->ship->getLength();
        $coords = [];

        for ($cell = 0; $cell < $length; $cell++) {
            if ($this->orientation == self::VERTICAL) {
                $y = $this->y_coord + $cell;
                $x = $this->x_coord;
            } else {
                $x = $this->x_coord + $cell;
                $y = $this->y_coord;
            }

            $coords[] = [
                'x' => $x,
                'y' => $y
            ];
        }

        return $coords;
    }

    private function getShipIdByName($name)
    {
        $ship = Ship::getShipByName($name);

        return $ship['id'];
    }

    public function getPlacedShips(Collection $ships): array
    {
        $data = [];
        foreach ($ships as $ship) {
            $data[] = Ship::getName($ship['ship_id']);
        }

        return $data;
    }

    private function deleteOneShip($shipData, $code): void
    {
        $this->where('player_id', '=', $code)->where('ship_id', '=', $this->getShipIdByName($shipData['ship']))->delete();

    }


    /**
     * Проверяет пересечение корабля с остальными объектами на поле
     * если корабль пересекается с другим кораблем -> возвраещает ошибку
     * если корабль выходит за границы поля -> возвраещает ошибку
     *
     * @param $shipData
     * @param $player
     * @return string|void
     */
    private function checkPosition($shipData, $player)
    {

        $length = $shipData['ship'][0];
        $field = $this->getMyField($player);
        $x = $shipData['x'];
        $y = $shipData['y'];

        $begin = $shipData['orientation'] == 'vertical' ? $y : $x;

        if ($begin < 0 || $begin + $length > 10) {
            return "Выход за границы поля!";
        }

        if($shipData['orientation'] == 'vertical') {
            $begin  = $shipData['y'];
            $edges = $shipData['x'];
        } else {
            $begin  = $shipData['x'];
            $edges =  $shipData['y'];
        }

        for ($edge = $edges - 1; $edge < $edges+2; $edge++) {

            for ($cell = $begin - 1; $cell < $length+$begin+1; $cell++) {

                if($shipData['orientation'] == 'vertical') {
                    $y = $cell;
                    $x = $edge;
                } else {
                    $x = $cell;
                    $y = $edge;
                }

                if (isset($field[$x][$y]) && ($field[$x][$y][0] != 'empty' && $field[$x][$y][0] != $shipData['ship'])) {
                    return "Пересечение с другим кораблем";
                }
            }

        }
    }

}
