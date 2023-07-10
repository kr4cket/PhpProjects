<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Ship
 *
 * @property int $id
 * @property string $name
 * @method static \Illuminate\Database\Eloquent\Builder|Ship newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ship newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ship query()
 * @method static \Illuminate\Database\Eloquent\Builder|Ship whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ship whereName($value)
 * @property-read \App\Models\ShipInSea|null $shipInSea
 * @mixin \Eloquent
 */
class Ship extends Model
{
    use HasFactory;


    public function shipInSea()
    {
        return $this->hasOne(ShipInSea::class, 'ship_id');
    }
    public static function getShipById($id)
    {
        return self::where('id', '=', $id)->first();
    }

    public static function getName($id)
    {
        $ship = self::getShipById($id);
        return $ship['name'];
    }

    public function getLength()
    {
        return $this->name[0];
    }

    public static function getShipByName($name)
    {
        return self::where('name', '=', $name)->first();
    }


}
