<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    protected $fillable = ['id', 'game_id','me_ready', 'my_turn'];
    public $timestamps = false;

    public function addPlayer(string $player, int $game)
    {
        $this->create([
            'id'        => $player, 
            'game_id'   => $game
        ]);
    }

    public function updateShotsData($shotData) 
    {
        $this->shots()->create([
            'player_id' => $this->id,
            'x_coord' => $shotData['x'],
            'y_coord' => $shotData['y']
        ]);
        
    }

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

}
