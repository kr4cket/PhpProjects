<?php


namespace App\Services;

use App\Models\Game;
use App\Models\Message;
use App\Models\Player;
use App\Models\Ship;
use App\Models\ShipInSea;
use App\Http\Resources\GameResource;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class GameService
{
    private $player;
    private $game;
    private $field;

    public function __construct(Player $player, Game $game, ShipInSea $ships)
    {
        $this->game = $game;
        $this->player = $player;
        $this->field = $ships;
    }

    public function createNewGame()
    {

        $game = $this->game->newGame();

        $this->createNewPlayer($game->id);
        $this->createNewPlayer($game->id);

        if(isset($game)) {

            return GameResource::make($game);

        }

        return [
            'success'   => false,
            'error'     => 101,
            'message'   => "Не удалось создать игру"
        ];

    }

    /**
     * Собирает всю информацию об игре
     *
     * @param Player $player
     * @param Game $game
     * @return array[]
     */
    public function getInfo(Player $player, Game $game): array
    {

        $info = [];
        $enemy = $game->getEnemy($player);

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
        $info['usedPlaces'] = $this->field->getPlacedShips($player->ships) ?? [];
        $info['success']    = true;

        return $info;

    }


    /**
     * Проверяет готовность игроков
     * Если оба игрока готовы -> игра переходит в активную стадию
     *
     * @param Game $game
     * @param Player $player
     * @return array
     */
    public function getReady(Game $game, Player $player)
    {

        $player->me_ready = 1;
        $player->save();
        $enemy = $game->getEnemy($player);

        if (boolval($player->me_ready) && boolval($enemy->me_ready)) {

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

    private function createNewPlayer(int $gameId)
    {
        $this->player->addPlayer($gameId);
    }

    public function isShipsPlaced(Player $player)
    {

        if ($player->ships->count() != Ship::count())
        {
            return false;
        }

        return true;
    }

    public function makeShot(Game $game, Player $player, array $shotData)
    {
        $shots = $player->shots;
        $enemy = $game->getEnemy($player);
        $data = [];

        foreach ($shots as $shot) {
            if ($shot->x_coord == $shotData['x'] && $shot->y_coord == $shotData['y']) {
                return $data;
            }
        }

        /*
        Пришлось конвертировать в массив, ввиду того, что не получалось обратиться
        к коллекции через foreach() (хотя с коллекцией выше все работает)
        */

        $enemyShips = $enemy->ships;
        $changeOrder = true;

        $player->updateShotsData($shotData);

        foreach ($enemyShips as $ship) {

            if ($this->field->fieldCheckShot($ship, $shotData)) {
                $changeOrder = false;
                $data['hit'] = true;
                $data['kill'] = $player->isShipDead($ship->getCoords());
                $enemy->health -= 1;
                break;
            }

        }

        if($enemy->health <= 0) {
            $game->endGame();
            $data['success'] = true;
            return $data;
        }

        if($changeOrder) {
            $game->user_order = $enemy->id;
            $player->my_turn = 0;
            $enemy->my_turn = 1;
        }

        $game->save();
        $enemy->save();
        $player->save();

        $data['success'] = true;
        return $data;
    }

}
