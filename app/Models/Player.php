<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * App\Models\Player
 *
 * @property string $id
 * @property int $game_id
 * @property int $me_ready
 * @property int $my_turn
 * @property int $health
 * @property-read \App\Models\Game $game
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Message> $messages
 * @property-read int|null $messages_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ShipInSea> $ships
 * @property-read int|null $ships_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Shot> $shots
 * @property-read int|null $shots_count
 * @method static \Illuminate\Database\Eloquent\Builder|Player newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Player newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Player query()
 * @method static \Illuminate\Database\Eloquent\Builder|Player whereGameId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Player whereHealth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Player whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Player whereMeReady($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Player whereMyTurn($value)
 * @mixin \Eloquent
 */
class Player extends Model
{
    use HasFactory;

    /**
     * @var int|mixed
     */
    protected $keyType = 'string';
    protected $fillable = ['id', 'game_id','me_ready', 'my_turn'];
    public $timestamps = false;

    public function ships()
    {
        return $this->hasMany(ShipInSea::class, 'player_id');
    }

    public function shots()
    {
        return $this->hasMany(Shot::class, 'player_id');
    }

    public function game()
    {
        return $this->belongsTo(Game::class);
    }
    public function addPlayer(int $game)
    {
        $this->create([
            'id'        => Str::random(10),
            'game_id'   => $game
        ]);
    }

    public function updateShotsData(array $shotData)
    {
        $this->shots()->create([
            'player_id' => $this->id,
            'x_coord' => $shotData['x'],
            'y_coord' => $shotData['y']
        ]);

    }



    public function messages()
    {
        return $this->hasMany(Message::class, 'player_id');
    }

}
