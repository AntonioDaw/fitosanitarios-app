<?php

namespace App\Http\Controllers;

use App\Http\Requests\TipoRequest;
use App\Http\Resources\TipoResource;
use App\Models\Tipo;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class TipoController extends Controller
{
    use ApiResponser;
public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $tiposPaginados = Tipo::paginate($perPage);
        $tiposResource = TipoResource::collection($tiposPaginados);

        return $this->paginatedResponse($tiposResource, $tiposPaginados);

    }

    public function show($id)
    {
        $tipo = Tipo::find($id);

        if (!$tipo) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tipo no encontrado'
            ], 404); // Not Found
        }

        return response()->json([
            'status' => 'success',
            'data' => new TipoResource($tipo)
        ], 200); // OK
    }

    // Metodo para crear un nuevo Tipo
    public function store(TipoRequest $request)
    {
        $tipo = Tipo::create($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Tipo creado correctamente.',
            'data' => new TipoResource($tipo)
        ], 201);
    }





    public function update(TipoRequest $request, Tipo $tipo)
    {
        $validated = $request->validated();

        $tipo->fill($validated);

        if ($tipo->isClean()) {
            return response()->json([
                'status' => 'info',
                'message' => 'No hay cambios que actualizar.'
            ], 200);
        }

        $tipo->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Tipo actualizado correctamente.',
            'data' => new TipoResource($tipo)
        ]);
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
