<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MissionController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/missions/create', [MissionController::class, 'create'])->name('missions.create');
Route::get('/move/{id}', [MissionController::class, 'moveView'])->name('missions.moveView');
