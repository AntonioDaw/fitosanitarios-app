<?php

use App\Http\Controllers\CultivoController;
use App\Http\Controllers\TipoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::get('/tipos/{id}', [TipoController::class, 'show']);
Route::get('/tipos', [TipoController::class, 'getAll']);
Route::post('/tipos', [TipoController::class, 'store']);
Route::put('/tipos/{id}', [TipoController::class, 'update']);
Route::delete('/tipos/{id}', [TipoController::class, 'delete']);

Route::get('/cultivos', [CultivoController::class, 'getAll']);
Route::get('/cultivos/{id}', [CultivoController::class, 'show']);
