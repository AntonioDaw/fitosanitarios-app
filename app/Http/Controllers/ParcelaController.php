<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreParcelaRequest;
use App\Models\Parcela;
use App\Models\Tipo;
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

    // Metodo para crear un nuevo Tipo
    public function store(StoreParcelaRequest $request)
    {
        $validated = $request->validated();

        $tipo = Parcela::create([
            'nombre' => $validated['nombre'],
            'numero_parcela' => $validated['numero_parcela'],
            'area' => $validated['area'],
        ]);



        return response()->json([
            'status' => 'success',
            'data' => $tipo
        ], 201); // Created
    }

    public function update(StoreParcelaRequest $request, $id)
    {
        // Encontramos el tipo por su ID
        $parcela = Parcela::find($id);

        // Si no se encuentra el tipo, devolvemos un error
        if (!$parcela) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tipo no encontrado'
            ], 404); // Not Found
        }

        // Validamos los datos a través de StoreTipoRequest
        $validated = $request->validated();

        // Actualizamos los campos del tipo con los datos validados
        $parcela->nombre = $validated['nombre'];
        $parcela->icono = $validated['icono'];

        // Guardamos el tipo actualizado en la base de datos
        $parcela->save();

        // Devolvemos el tipo actualizado
        return response()->json([
            'status' => 'success',
            'data' => $parcela
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
