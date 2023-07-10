<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Player;

/**
 * App\Models\Game
 *
 * @property int $id
 * @property int $status
 * @property string $user_order
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Player> $players
 * @property-read int|null $players_count
 * @method static \Illuminate\Database\Eloquent\Builder|Game newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Game newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Game query()
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereUserOrder($value)
 * @mixin \Eloquent
 */
class Game extends Model
{
    use HasFactory;

    protected $fillable = ['code','user_order', 'user_order'];
    public $timestamps = false;
    private $playerColumn = 'code';
    const BEGIN = 1;
    const IN_PROCESS = 2;
    const END = 3;


    public function players()
    {
        return $this->hasMany(Player::class, 'game_id');
    }
    public function newGame(): object
    {
        $game = $this->create([
            'status'        => self::BEGIN,
            'user_order'    => ''
        ]);

        return $game;
    }

    public function startGame()
    {
        $player = $this->players->random();
        $player->my_turn = 1;
        $this->status = self::IN_PROCESS;
        $this->user_order = $player->id;
        $player->save();
        $this->save();
    }

    public function endGame()
    {
        $this->status = self::END;
        $this->save();
    }

    public function getEnemy(Player $player)
    {
        $players = $this->players;
        return $player->id == $players[0]->id ? $players[1] : $players[0];
    }


}
