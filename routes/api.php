<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MapController;
use App\Http\Controllers\RoverController;
use App\Http\Controllers\ObstacleController;
use App\Http\Controllers\MissionController;

Route::middleware('api')->group(function () {
    Route::apiResource('maps', MapController::class);
    Route::apiResource('rovers', RoverController::class);
    Route::apiResource('obstacles', ObstacleController::class);
    Route::apiResource('missions', MissionController::class);
    
    Route::post('/missions/{id}/move', [MissionController::class, 'move']);
});
