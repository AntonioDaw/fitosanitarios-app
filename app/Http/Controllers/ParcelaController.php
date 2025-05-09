<?php

namespace App\Http\Controllers;

use App\Http\Requests\ParcelaRequest;
use App\Models\Parcela;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class ParcelaController extends Controller
{
    use ApiResponser;
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $parcelasPaginadas = Parcela::paginate($perPage);


        return $this->paginatedResponse($parcelasPaginadas->items(), $parcelasPaginadas);
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
    public function store(ParcelaRequest $request)
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

    public function update(ParcelaRequest $request, $id)
    {
        // Encontramos el parcela por su ID
        $parcela = Parcela::find($id);

        // Si no se encuentra el parcela, devolvemos un error
        if (!$parcela) {
            return response()->json([
                'status' => 'error',
                'message' => 'Parcela no encontrada'
            ], 404); // Not Found
        }

        // Validamos los datos a través de ParcelaRequest
        $validated = $request->validated();

        // Actualizamos los campos del parcela con los datos validados
        $parcela->nombre = $validated['nombre'];
        $parcela->numero_parcela = $validated['numero_parcela'];
        $parcela->area = $validated['area'];

        // Guardamos el parcela actualizado en la base de datos
        $parcela->save();

        // Devolvemos el parcela actualizado
        return response()->json([
            'status' => 'success',
            'data' => $parcela
        ], 200);
    }

    public function delete($id){
        // Buscar la parcela antes de eliminarla
        $parcela = Parcela::find($id);

        // Verificar si la parcela existe
        if (!$parcela) {
            return response()->json([
                'status' => 'error',
                'message' => 'Parcela no encontrada.'
            ], 404); // 404 Not Found
        }

        // Eliminar la parcela
        $parcela->delete();

        // Retornar la respuesta con la parcela eliminada
        return response()->json([
            'status' => 'success',
            'data' => $parcela
        ], 200); // 200 OK
    }
}
