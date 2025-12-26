<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CastleController; 
use App\Http\Controllers\UserController;

// Главная страница Laravel (от Breeze)
Route::get('/', function () {
    return view('welcome');
});

// Маршруты для замков (только по адресу /castles)
Route::get('/castles', [CastleController::class, 'index'])->name('castles.index');
Route::resource('castles', CastleController::class); // CRUD для замков

Route::get('/users/{user:name}/castles', [CastleController::class, 'index'])->name('users.castles');
Route::get('/users', [UserController::class, 'index'])->name('users.index');

// Дашборд от Breeze
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Профиль от Breeze
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('castles/{id}/purge', [CastleController::class, 'purge'])->name('castles.purge')->middleware(['auth']);

Route::post('castles/{id}/restore', [CastleController::class, 'restore'])->name('castles.restore')->middleware(['auth']);

// Аутентификация от Breeze
require __DIR__.'/auth.php';