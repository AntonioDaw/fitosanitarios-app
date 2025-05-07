<?php

namespace App\Http\Controllers;

use App\Models\Parcela;
use Illuminate\Http\Request;

class ParcelaController extends Controller
{
    public function show()
    {
        $parcela = Parcela::all();
        return response()->json($parcela); // Devolvemos la parcela como JSON
    }
}
