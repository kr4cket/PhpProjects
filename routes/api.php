<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\GameController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ShipInSeaController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware([])->group( function () {

    Route::post('/start',[GameController::class,'start']);
    Route::post('/status/{game}/{player}',[GameController::class,'status']);
    Route::post('/place-ship/{game}/{player}',[ShipInSeaController::class,'place']);
    Route::post('/clear-field/{game}/{player}',[ShipInSeaController::class,'clear']);
    Route::post('/ready/{game}/{player}',[GameController::class,'ready']);
    Route::post('/shot/{game}/{player}',[ShipInSeaController::class,'shot']);
    Route::post('/chat-send/{game}/{player}', [MessageController::class, 'send']);
    Route::get('/chat-load/{game}/{player}', [MessageController::class, 'get']);
});



