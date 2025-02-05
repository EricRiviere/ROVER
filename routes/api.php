<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MapController;
use App\Http\Controllers\RoverController;
use App\Http\Controllers\ObstacleController;
use App\Http\Controllers\MissionController;
use App\Http\Controllers\ExplorationMapsController;

Route::middleware('api')->group(function () {
    Route::apiResource('maps', MapController::class);
    Route::apiResource('rovers', RoverController::class);
    Route::apiResource('obstacles', ObstacleController::class);
    Route::apiResource('missions', MissionController::class);
    Route::apiResource('exploration-maps', ExplorationMapsController::class);

    Route::post('/missions/{id}/move', [MissionController::class, 'move']);
    Route::post('/missions/{missionId}/exploration-map', [ExplorationMapsController::class, 'createForMission']);
    Route::post('/missions/{id}/finish', [MissionController::class, 'finishMission']);
    Route::post('/exploration-maps/{explorationMapId}/explore-obstacle', [ExplorationMapsController::class, 'registerExplorationData']);
    Route::get('/exploration-maps/{explorationMapId}/exploration-data', [ExplorationMapsController::class, 'getExplorationData']);
});
