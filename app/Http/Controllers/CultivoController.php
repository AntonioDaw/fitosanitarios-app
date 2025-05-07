<?php

namespace App\Http\Controllers;

use App\Http\Resources\CultivoResource;
use App\Models\Cultivo;
use Illuminate\Http\Request;

class CultivoController extends Controller
{
    public function getAll()
    {
        $cultivos = CultivoResource::collection(Cultivo::all());

        return response()->json([
            'status' => 'success',
            'data' => $cultivos
        ], 200); // OK
    }

    public function show($id)
    {
        $cultivo = Cultivo::find($id);

        if (!$cultivo) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cultivo no encontrado',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => new CultivoResource($cultivo)
        ], 200);
    }
}
