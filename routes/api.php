<?php

use App\Http\Controllers\CultivoController;
use App\Http\Controllers\ParcelaController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\SectorController;
use App\Http\Controllers\TipoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::get('/tipos/{id}', [TipoController::class, 'show']);
Route::get('/tipos', [TipoController::class, 'index']);
Route::post('/tipos', [TipoController::class, 'store']);
Route::put('/tipos/{tipo}', [TipoController::class, 'update']);
Route::delete('/tipos/{id}', [TipoController::class, 'delete']);

Route::get('/cultivos', [CultivoController::class, 'index']);
Route::get('/cultivos/{id}', [CultivoController::class, 'show']);
Route::post('/cultivos', [CultivoController::class, 'store']);
Route::put('/cultivos/{cultivo}', [CultivoController::class, 'update']);
Route::delete('/cultivos/{id}', [CultivoController::class, 'delete']);
Route::post('/cultivos/plantar/{cultivoId}', [CultivoController::class, 'plantarSector']);
Route::get('/tipos/{tipoId}/cultivos', [CultivoController::class, 'cultivosTipo']);

Route::get('/parcelas', [ParcelaController::class, 'index']);
Route::get('/parcelas/{id}', [ParcelaController::class, 'show']);
Route::post('/parcelas', [ParcelaController::class, 'store']);
Route::put('/parcelas/{id}', [ParcelaController::class, 'update']);
Route::delete('/parcelas/{id}', [ParcelaController::class, 'delete']);

Route::get('/sectores', [SectorController::class, 'index']);
Route::post('/sectores', [SectorController::class, 'store']);
Route::get('/sectores/vacios', [SectorController::class, 'sectoresSinCultivos']);
Route::get('/sectores/{id}', [SectorController::class, 'show']);
Route::put('/sectores/{sector}', [SectorController::class, 'update']);
Route::delete('/sectores/{id}', [SectorController::class, 'delete']);
Route::get('/sectores/cultivos/{nombre}', [SectorController::class, 'sectoresCultivos']);

Route::get('/proveedores', [ProveedorController::class, 'index']);
Route::get('/proveedores/{id}', [ProveedorController::class, 'show']);
Route::post('/proveedores', [ProveedorController::class, 'store']);
Route::put('/proveedores/{proveedor}', [ProveedorController::class, 'update']);
Route::delete('/proveedores/{id}', [ProveedorController::class, 'delete']);
Route::patch('/proveedores/{id}/toggle-estado', [ProveedorController::class, 'toggleEstado']);
