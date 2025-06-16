<?php


use App\Http\Controllers\TipoController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


//Route::get('/tipos/{id}', [TipoController::class, 'show']);
//Route::get('/tipos', [TipoController::class, 'getAll']);
Route::get('/', function () {
    return 'Laravel funcionando 🎉';
});
Route::get('/clear-cache', function() {
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    Artisan::call('cache:clear');
    return 'Cache limpiada y recacheada';
});
