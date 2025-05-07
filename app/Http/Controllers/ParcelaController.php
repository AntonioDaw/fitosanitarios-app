<?php

namespace App\Http\Controllers;

use App\Models\Parcela;
use Illuminate\Http\Request;

class ParcelaController extends Controller
{
    public function getAll()
    {
        $parcelas = Parcela::all();

        return response()->json([
            'status' => 'success',
            'data' => $parcelas
        ], 200); // OK
    }

    public function show($id)
    {
        $parcela = Parcela::find($id);

        if (!$parcela) {
            return response()->json([
                'status' => 'error',
                'message' => 'Parcela no encontrado'
            ], 404); // Not Found
        }

        return response()->json([
            'status' => 'success',
            'data' => $parcela
        ], 200); // OK
    }
}
