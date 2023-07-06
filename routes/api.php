<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\GameController;
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

Route::middleware(['cors'])->group(function () {
    
    Route::post('/start',[GameController::class,'start']);
    Route::post('/status/{id}/{code}',[GameController::class,'status']);
    Route::post('/place-ship/{id}/{code}',[ShipInSeaController::class,'place']);
    Route::post('/clear-field/{id}/{code}',[ShipInSeaController::class,'clear']);
    Route::post('/ready/{id}/{code}',[GameController::class,'ready']);
});

