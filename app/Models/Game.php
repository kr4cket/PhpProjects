<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Player;

class Game extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'invite', 'status', 'user_order'];
    public $timestamps = false;
    const BEGIN = 1;
    const IN_PROCESS = 2;
    const END = 3;

    public function newGame()
    {
        $game = $this->create([
            'code'          => $this->generateCode(),
            'invite'        => $this->generateCode(),
            'status'        => self::BEGIN,
            'user_order'    => ''
        ]);

        return $game;

    }

    private function generateCode()
    {
        return bin2hex(random_bytes(5));
    }

    public function getGameInfo($id, $code, ShipInSea $sea, Shot $shots)
    {
        $info = [
            'game' => [
                'id' => $id
            ]
        ];
        
        $game = $this->where('id', '=', $id)->first();
        $playerCode = $game['code'];

        if ($code == $playerCode) {
            $info['game']['invite'] = $game['invite'];
            $field = $sea->getFields($code, $game['invite'], $shots);
        } else {
            $info['game']['invite'] = $game['code'];
            $field = $sea->getFields($code, $game['code'], $shots);
        }
        
        $info['fieldMy'] = $field['fieldMy'];
        $info['fieldEnemy'] = $field['fieldEnemy'];
        $info['game']['myTurn'] = false;
        $info['game']['meReady'] = true;
        $info['success'] = true;
        $info['usedPlaces'] = [];

        return $info;
    }

}
