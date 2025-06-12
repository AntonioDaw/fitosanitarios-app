<?php

use App\Http\Controllers\AplicacionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CultivoController;
use App\Http\Controllers\ParcelaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\SectorController;
use App\Http\Controllers\TipoController;
use App\Http\Controllers\TratamientoController;
use App\Http\Controllers\UnidadProductoController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login']);
Route::middleware('auth:api')->group(function () {
Route::get('/tipos/{id}', [TipoController::class, 'show']);
Route::get('/tipos', [TipoController::class, 'index']);
    Route::middleware(RoleMiddleware::class)->group(function () {
        Route::post('/tipos', [TipoController::class, 'store']);
        Route::put('/tipos/{tipo}', [TipoController::class, 'update']);
        Route::delete('/tipos/{id}', [TipoController::class, 'delete']);
    });


Route::get('/cultivos', [CultivoController::class, 'index']);
Route::get('/cultivos/{id}', [CultivoController::class, 'show']);
Route::get('/tipos/{tipoId}/cultivos', [CultivoController::class, 'cultivosTipo']);
Route::get('/listacultivos', [CultivoController::class, 'listaCultivos']);
    Route::middleware(RoleMiddleware::class)->group(function () {
Route::post('/cultivos', [CultivoController::class, 'store']);
Route::put('/cultivos/{cultivo}', [CultivoController::class, 'update']);
Route::delete('/cultivos/{id}', [CultivoController::class, 'delete']);

    });
    Route::post('/cultivos/plantar/{cultivoId}', [CultivoController::class, 'plantarSector']);


Route::get('/parcelas', [ParcelaController::class, 'index']);
Route::get('/parcelas/{id}', [ParcelaController::class, 'show']);
    Route::middleware(RoleMiddleware::class)->group(function () {
        Route::post('/parcelas', [ParcelaController::class, 'store']);
        Route::put('/parcelas/{parcela}', [ParcelaController::class, 'update']);
        Route::delete('/parcelas/{id}',[ParcelaController::class, 'delete']);
    });



Route::get('/sectores', [SectorController::class, 'index']);
Route::get('/sectores/vacios', [SectorController::class, 'sectoresSinCultivos']);
Route::get('/sectores/tipo', [SectorController::class, 'sectoresCultivos']);
Route::get('/sectores/{id}', [SectorController::class, 'show']);
    Route::middleware(RoleMiddleware::class)->group(function () {
        Route::post('/sectores', [SectorController::class, 'store']);
        Route::put('/sectores/{sector}', [SectorController::class, 'update']);
        Route::delete('/sectores/{id}', [SectorController::class, 'delete']);
    });
Route::get('/sectores/cultivos/{nombre}', [SectorController::class, 'sectoresCultivos']);
Route::get('/sectores/tipo', [SectorController::class, 'sectoresCultivos']);

Route::get('/proveedores', [ProveedorController::class, 'index']);
Route::get('/proveedores/{id}', [ProveedorController::class, 'show']);
    Route::middleware(RoleMiddleware::class)->group(function () {
Route::post('/proveedores', [ProveedorController::class, 'store']);
Route::put('/proveedores/{proveedor}', [ProveedorController::class, 'update']);
Route::delete('/proveedores/{id}', [ProveedorController::class, 'delete']);
Route::patch('/proveedores/{id}/toggle-estado', [ProveedorController::class, 'toggleEstado']);
    });

Route::get('productos', [ProductoController::class, 'index']);
Route::get('productos/{producto}', [ProductoController::class, 'show']);
    Route::middleware(RoleMiddleware::class)->group(function () {
Route::post('productos', [ProductoController::class, 'store']);
Route::put('productos/{producto}', [ProductoController::class, 'update']);
Route::patch('productos/{producto}', [ProductoController::class, 'update']);
Route::delete('productos/{id}', [ProductoController::class, 'destroy']);
    });
Route::get('/listaproductos', [ProductoController::class, 'listaProductos']);

Route::get('unidadproductos', [UnidadProductoController::class, 'index']);
Route::get('unidadproductos/{id}', [UnidadProductoController::class, 'show']);
    Route::middleware(RoleMiddleware::class)->group(function () {
Route::post('unidadproductos', [UnidadProductoController::class, 'store']);
Route::put('unidadproductos/{unidadProducto}', [UnidadProductoController::class, 'update']);
Route::delete('unidadproductos/{id}', [UnidadProductoController::class, 'destroy']);
Route::post('/unidadproductos/lote', [UnidadProductoController::class, 'storeLote']);
    });


Route::get('tratamientos', [TratamientoController::class, 'index']);
Route::get('tratamientos/form', [TratamientoController::class, 'tratamientosForm']);
Route::get('tratamientos/{id}', [TratamientoController::class, 'show']);
    Route::middleware(RoleMiddleware::class)->group(function () {
Route::post('tratamientos', [TratamientoController::class, 'store']);
Route::put('tratamientos/{tratamiento}', [TratamientoController::class, 'update']);
Route::delete('tratamientos/{id}', [TratamientoController::class, 'destroy']);
Route::post('/tratamientos/{id}/avanzar', [TratamientoController::class, 'avanzarEstado']);
    });
Route::get('tratamientos/tipo/{id}', [TratamientoController::class, 'tratamientosTipo']);


Route::post('aplicaciones', [AplicacionController::class, 'store']);
Route::get('aplicaciones', [AplicacionController::class, 'index']);
Route::get('aplicaciones/{id}', [AplicacionController::class, 'show']);
    Route::middleware(RoleMiddleware::class)->group(function () {
Route::post('aplicaciones/aprobar/{id}', [AplicacionController::class, 'aprobar']);
Route::post('aplicaciones/rechazar/{id}', [AplicacionController::class, 'rechazar']);
    });

});





