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

    public function getGameInfo($id, $code, ShipInSea $sea, Shot $shots, Player $player)
    {
        $info = [
            'game' => [
                'id' => $id
            ]
        ];
        
        $game = $this->where('id', '=', $id)->first();
        $playerCode = $game['code'];

        if ($code == $playerCode) {
            $me = $code;
            $enemy = $game['invite'];
        } else {
            $me = $code;
            $enemy = $game['code'];
        }

        $field = $sea->getFields($me, $enemy, $shots);
        
        $info['game']['invite'] = $enemy;
        $info['fieldMy'] = $field['fieldMy'];
        $info['fieldEnemy'] = $field['fieldEnemy'];
        $info['game']['myTurn'] = boolval($player->getTurn($me));
        $info['game']['meReady'] = boolval($player->getReady($me));
        $info['success'] = true;
        $info['usedPlaces'] = $sea->getPlacedShips($code) ?? [];

        return $info;
    }

    public function getReady($id, $code, Player $player)
    {
        $data = [];
        $game = $this->where('id', '=', $id)->first();

        $playerCode = $game['code'];
        $enemy = ($code == $playerCode) ? $game['invite'] : $game['code'];

        $data['enemyReady'] = boolval($player->getReady($enemy));
        $data['success'] = !empty($data) ? true : false;

        $player->setReady($code);

        return $data;
    }

}
