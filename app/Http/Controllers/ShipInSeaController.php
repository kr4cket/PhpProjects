<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShipInSea;
use App\Models\Game;
use App\Models\Player;
use App\Models\Shot;
use App\Services\GameService;

class ShipInSeaController extends Controller
{
    public function place(Game $id, Request $request, Player $code, GameService $service)
    {
        $postData = $request->post();
        $error = $service->placeShips($postData, $code);

        if (empty($error)) {
            
            return response()->json([
                'success'   => true,
            ]);
        }

        return response()->json([
            'success'   => false,
            'error'     => 104,
            'message'   => $error
        ]);
    }

    public function shot(Game $id, Player $code, GameService $service, Request $request)
    {
        $data = $request->post();
        $response = $service->makeShot($id, $code,$data);

        if($response) {
            return response()->json([
                'success' => $response,
            ]);
        }

        return response()->json([
            'success'   => false,
            'error'     => 105,
            'message'   => 'Сюда уже стреляли'
        ]);
    }

    public function clear(Game $id, Player $code, GameService $service) 
    {
        return response()->json([
            'success'   => $service->clearField($code) ?? false,
        ]);
    }
}
