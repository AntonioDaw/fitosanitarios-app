<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTipoRequest;
use App\Models\Tipo;
use Illuminate\Http\Request;

class TipoController extends Controller
{

    public function getAll()
    {
        $tipos = Tipo::all();

        return response()->json([
            'status' => 'success',
            'data' => $tipos
        ], 200); // OK
    }

    public function show($id)
    {
//        // Validación explícita
//        $validated = validator()->make(['id' => $id], [
//            'id' => 'required|integer|exists:tipos,id',
//        ]);
//
//        if ($validated->fails()) {
//            return response()->json([
//                'status' => 'error',
//                'message' => 'ID inválido'
//                ], 400);
//        }
        $tipo = Tipo::find($id);

        if (!$tipo) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tipo no encontrado'
            ], 404); // Not Found
        }

        return response()->json([
            'status' => 'success',
            'data' => $tipo
        ], 200); // OK
    }

    // Metodo para crear un nuevo Tipo
    public function store(StoreTipoRequest $request)
    {
        $validated = $request->validated();

        $tipo = Tipo::create([
            'nombre' => $validated['nombre'],
            'icono' => $validated['icono'],
        ]);



        return response()->json([
            'status' => 'success',
            'data' => $tipo
        ], 201); // Created
    }

    public function update(StoreTipoRequest $request, $id)
    {
        // Encontramos el tipo por su ID
        $tipo = Tipo::find($id);

        // Si no se encuentra el tipo, devolvemos un error
        if (!$tipo) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tipo no encontrado'
            ], 404); // Not Found
        }

        // Validamos los datos a través de StoreTipoRequest
        $validated = $request->validated();

        // Actualizamos los campos del tipo con los datos validados
        $tipo->nombre = $validated['nombre'];
        $tipo->icono = $validated['icono'];

        // Guardamos el tipo actualizado en la base de datos
        $tipo->save();

        // Devolvemos el tipo actualizado
        return response()->json([
            'status' => 'success',
            'data' => $tipo
        ], 200);
    }

    public function delete($id){
        // Buscar el tipo antes de eliminarlo
        $tipo = Tipo::find($id);

        // Verificar si el tipo existe
        if (!$tipo) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tipo no encontrado.'
            ], 404); // 404 Not Found
        }

        // Eliminar el tipo
        $tipo->delete();

        // Retornar la respuesta con el tipo eliminado
        return response()->json([
            'status' => 'success',
            'data' => $tipo
        ], 200); // 200 OK
    }
}
