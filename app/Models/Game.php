<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Player;

class Game extends Model
{
    use HasFactory;

    protected $fillable = ['code','user_order', 'user_order'];
    public $timestamps = false;
    private $playerColumn = 'code';
    const BEGIN = 1;
    const IN_PROCESS = 2;
    const END = 3;

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

    public function enemyColumn(string $columnName)
    {
        $this->playerColumn = $columnName;
    }

    public function players()
    {
        return $this->hasMany(Player::class, 'game_id');
    }    


}
