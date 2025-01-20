<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\IngredienteController;
use App\Http\Controllers\PizzaController;   
 
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api')->name('logout');
    Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('auth:api')->name('refresh');
    Route::post('/me', [AuthController::class, 'me'])->middleware('auth:api')->name('me');
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'ingredientes'
], function ($router) {
    Route::get('/', [IngredienteController::class, 'index'])->middleware('auth:api')->name('index');
    Route::post('/', [IngredienteController::class, 'store'])->name('store');
    Route::put('/{id}', [IngredienteController::class, 'update'])->name('update');
    Route::delete('/{id}', [IngredienteController::class, 'destroy'])->middleware('auth:api')->name('destroy');
   
});
Route::group([
    'middleware' => 'api',
    'prefix' => 'pizzas'
], function ($router) {
    Route::get('/', [PizzaController::class, 'index'])->name('index');
    Route::post('/', [PizzaController::class, 'store'])->middleware('auth:api')->name('store');
    Route::put('/{id}', [PizzaController::class, 'update'])->middleware('auth:api')->name('update');
    Route::get('/{id}', [PizzaController::class, 'show'])->name('show');
    Route::patch('/{id}/ingredientes/', [PizzaController::class, 'actualizarIngredientes'])->middleware('auth:api')->name('actuzalizarIngredientes');
   
});
