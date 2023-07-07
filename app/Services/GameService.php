<?php


namespace App\Services;

use App\Models\Game;
use App\Models\Message;
use App\Models\Player;
use App\Models\ShipInSea;
use App\Http\Resources\GameResource;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class GameService
{
    private $player;
    private $game;
    private $field;
    private $messages;

    public function __construct(Player $player, Game $game, ShipInSea $ships, Message $messages)
    {
        $this->game = $game;
        $this->player = $player;
        $this->field = $ships;
        $this->messages = $messages;
    }

    public function createNewGame()
    {

        $game = $this->game->newGame();

        $this->createNewPlayer($game->id);
        $this->createNewPlayer($game->id);

        if(isset($game)) {
            return response()->json(GameResource::make($game));
        }
        return response()->json([
            'success'   => false,
            'error'     => 101,
            'message'   => "Не удалось создать игру"
        ]);

    }

    public function getInfo(Player $player, Game $game)
    {
        $info = [];
        $players = $game->players;
        $enemy = $player->id == $players[0]->id ? $players[1] : $players[0];

        $info = [
            'game' => [
                'id'        => $game->id,
                'invite'    => $enemy->id,
                'myTurn'    => boolval($player->my_turn),
                'meReady'   => boolval($player->me_ready),
                'status'    => $game->status
            ]
        ];

        $info['fieldMy']    = $this->field->getField($player->ships, $enemy->shots,  false);
        $info['fieldEnemy'] = $this->field->getField($enemy->ships, $player->shots, true);
        $info['usedPlaces'] = $this->field->getPlacedShips($player->id) ?? [];
        $info['success']    = true;

        return $info;

    }

    public function getReady(Game $game, Player $player)
    {
        $player->me_ready = 1;
        $player->save();
        $players = $game->players;
        $enemy = $player->id == $players[0]->id ? $players[1] : $players[0];

        if ($player->me_ready == $enemy->me_ready) {
            $game->startGame();
        }
        
        return ['success' => true, 'enemyReady' => boolval($enemy->me_ready)];
    }

    public function placeShips(array $postData, Player $player) 
    {

        if($postData) 
        {
            if (array_key_exists('ships', $postData)) {
                $messages = $this->field->addAllShips($postData['ships'], $player);
            } else {
                $messages = $this->field->addOneShip($postData, $player);
            }
        }

        return $messages;
    }

    public function clearField(Player $player)
    {
        $this->field->clear($player);
        return true;
    }

    private function createNewPlayer($gameId)
    {
        $playerCode = bin2hex(random_bytes(5));
        $this->player->addPlayer($playerCode, $gameId);
    }

    public function makeShot(Game $game, Player $player, $shotData)
    {
        $shots = $player->shots;
        $players = $game->players;
        $enemy = $player->id == $players[0]->id ? $players[1] : $players[0];

        foreach ($shots as $shot) {
            if ($shot->x_coord == $shotData['x'] && $shot->y_coord == $shotData['y']) {
                return false;
            }
        }

        /*
        Пришлось конвертировать в массив, ввиду того, что не получалось обратиться 
        к коллекции через foreach() (хотя с коллекцией выше все работает)
        */

        $enemyShips = $enemy->ships->toArray();
        $changeOrder = true;

        foreach ($enemyShips as $ship) {

            if ($this->field->fieldCheckShot($ship, $shotData)) {
                $changeOrder = false;
                $enemy->health -= 1;
                break;
            }

        }

        $player->updateShotsData($shotData);
        
        if($enemy->health <= 0) {
            $game->endGame();
            return true;
        }

        if($changeOrder) {
            $game->user_order = $enemy->id;
            $player->my_turn = 0;
            $enemy->my_turn = 1;
        } 

        $game->save();
        $player->save();
        $enemy->save();

        return true;
    }

}