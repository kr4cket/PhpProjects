<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ship extends Model
{
    use HasFactory;

    public static function getShipById($id)
    {
        return self::where('id', '=', $id)->first();
    }

    public static function getName($id)
    {
        $ship = self::getShipById($id);
        return $ship['name'];
    }

    public static function getShipByName($name)
    {
        return self::where('name', '=', $name)->first();
    }
}
