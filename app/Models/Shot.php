<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Shot
 *
 * @property string $player_id
 * @property int $x_coord
 * @property int $y_coord
 * @method static \Illuminate\Database\Eloquent\Builder|Shot newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Shot newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Shot query()
 * @method static \Illuminate\Database\Eloquent\Builder|Shot wherePlayerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shot whereXCoord($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shot whereYCoord($value)
 * @property string $id
 * @method static \Illuminate\Database\Eloquent\Builder|Shot whereId($value)
 * @mixin \Eloquent
 */
class Shot extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    protected $fillable = ['player_id', 'x_coord','y_coord'];
    public $timestamps = false;
}
