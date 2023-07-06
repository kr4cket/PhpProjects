<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShipInSea;

class ShipInSeaController extends Controller
{
    public function place($id, $code, ShipInSea $ships)
    {
        $postData = $_POST;
        if ($postData) {

            if (array_key_exists('ships', $postData)) {
                $error = $ships->addAllShips($postData['ships'], $code);
            } else {
                $error = $ships->addOneShip($postData, $code);
            }
    
        }

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

    public function clear($id, $code, ShipInSea $field) 
    {
        return response()->json([
            'success'   => $field->clear($code),
        ]);
    }
}
