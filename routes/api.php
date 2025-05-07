<?php

use App\Http\Controllers\CultivoController;
use App\Http\Controllers\ParcelaController;
use App\Http\Controllers\SectorController;
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
Route::post('/cultivos', [CultivoController::class, 'store']);
Route::put('/cultivos/{id}', [CultivoController::class, 'update']);
Route::delete('/cultivos/{id}', [CultivoController::class, 'delete']);

Route::get('/parcelas', [ParcelaController::class, 'getAll']);
Route::get('/parcelas/{id}', [ParcelaController::class, 'show']);
Route::post('/parcelas', [ParcelaController::class, 'store']);
Route::put('/parcelas/{id}', [ParcelaController::class, 'update']);
Route::delete('/parcelas/{id}', [ParcelaController::class, 'delete']);

Route::get('/sectores', [SectorController::class, 'getAll']);
Route::get('/sectores/{id}', [SectorController::class, 'show']);
Route::post('/sectores', [SectorController::class, 'store']);
Route::put('/sectores/{id}', [SectorController::class, 'update']);
Route::delete('/sectores/{id}', [SectorController::class, 'delete']);
