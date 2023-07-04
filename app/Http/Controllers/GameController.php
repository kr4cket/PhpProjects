<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;

class GameController extends Controller
{
    private $model;

    public function __construct()
    {
        $this->model = new Game();
    }

    public function start()
    {
        $gameData = [];
        $success = $this->model->createNewRecord();

        if ($success) {
            $gameData = $this->model->getData();
        } else {
            $gameData = $this->model->getErrorData();
        }

        $gameData['success'] = $success;

        return response()->json($gameData);
    }


    // public function index()
    // {

    // }

    // public function store(Request $request)
    // {

    // }

 
    // public function show(Game $game)
    // {
    // }

    // public function update(Request $request, Game $game)
    // {

    // }

    // public function destroy(Game $game)
    // {

    // }
}
