<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCultivoRequest;
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

    // Metodo para crear un nuevo Cultivo
    public function store(StoreCultivoRequest $request)
    {
        $validated = $request->validated();

        $cultivo = Cultivo::create([
            'nombre' => $validated['nombre'],
            'tipo_id' => $validated['tipo_id'],
        ]);



        return response()->json([
            'status' => 'success',
            'data' => $cultivo
        ], 201); // Created
    }

    public function update(StoreCultivoRequest $request, $id)
    {
        // Encontramos el cultivo por su ID
        $cultivo = Cultivo::find($id);

        // Si no se encuentra el cultivo, devolvemos un error
        if (!$cultivo) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tipo no encontrado'
            ], 404); // Not Found
        }

        // Validamos los datos a través de StoreTipoRequest
        $validated = $request->validated();

        // Actualizamos los campos del cultivo con los datos validados
        $cultivo->nombre = $validated['nombre'];
        $cultivo->tipo_id = $validated['tipo_id'];

        // Guardamos el cultivo actualizado en la base de datos
        $cultivo->save();

        // Devolvemos el cultivo actualizado
        return response()->json([
            'status' => 'success',
            'data' => $cultivo
        ], 200);
    }

    public function delete($id){
        // Buscar el cultivo antes de eliminarlo
        $cultivo = Cultivo::find($id);

        // Verificar si el cultivo existe
        if (!$cultivo) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cultivo no encontrado.'
            ], 404); // 404 Not Found
        }

        // Eliminar el tipo
        $cultivo->delete();

        // Retornar la respuesta con el tipo eliminado
        return response()->json([
            'status' => 'success',
            'data' => $cultivo
        ], 200); // 200 OK
    }
}

