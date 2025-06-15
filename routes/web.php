<?php


use App\Http\Controllers\TipoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


//Route::get('/tipos/{id}', [TipoController::class, 'show']);
//Route::get('/tipos', [TipoController::class, 'getAll']);
Route::get('/', function () {
    return 'Laravel funcionando 🎉';
});
