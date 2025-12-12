<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CastleController;
use App\Models\Castle;

Route::get('/', [CastleController::class, 'index'])->name('home');
Route::get('/castles', [CastleController::class, 'index'])->name('castles.index');

// Ресурсные маршруты для CRUD операций
Route::resource('castles', CastleController::class);
