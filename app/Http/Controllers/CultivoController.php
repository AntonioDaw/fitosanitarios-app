<?php

namespace App\Http\Controllers;

use App\Http\Requests\CultivoRequest;
use App\Http\Resources\CultivoResource;
use App\Models\Cultivo;
use App\Models\Sector;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;



class CultivoController extends Controller
{
    use ApiResponser;

    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search');
        $sortBy = $request->input('sort_by', 'id'); // columna por defecto
        $sortDir = $request->input('sort_dir', 'asc'); // dirección por defecto

        $query = Cultivo::query();

        if ($search) {
            $query->where('nombre', 'like', "%{$search}%");
        }

        // Validar sortDir para evitar valores inválidos
        if (!in_array(strtolower($sortDir), ['asc', 'desc'])) {
            $sortDir = 'asc';
        }

        $query->orderBy($sortBy, $sortDir);

        $cultivosPaginados = $query->paginate($perPage);

        $cultivosResource = CultivoResource::collection($cultivosPaginados);

        return $this->paginatedResponse($cultivosResource, $cultivosPaginados);
    }

    public function listaCultivos(Request $request)
    {
        $cultivos = Cultivo::all();
        return CultivoResource::collection($cultivos);
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

    public function store(CultivoRequest $request)
    {
        $cultivo = Cultivo::create($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Cultivo creado correctamente.',
            'data' => new CultivoResource($cultivo)
        ], 201);
    }

    public function update(CultivoRequest $request, Cultivo $cultivo)
    {
        $validated = $request->validated();

        $cultivo->fill($validated);

        if ($cultivo->isClean()) {
            return response()->json([
                'status' => 'info',
                'message' => 'No hay cambios que actualizar.'
            ], 200);
        }
        // Bloquear edición si el cultivo está plantado en algún sector
        if ($cultivo->sectores()->exists()) {
            return response()->json([
                'message' => 'No se puede modificar un cultivo que ya está plantado en un sector.'
            ], 403);
        }
        $cultivo->save();

        return response()->json([
            'status' => 'success',
            'data' => new CultivoResource($cultivo->fresh())
        ]);
    }

    public function delete($id)
    {
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


    public function plantarSector(Request $request, $cultivoId)
    {
        $request->validate([
            'sector_id' => 'required|exists:sectors,id'
        ]);

        $cultivo = Cultivo::findOrFail($cultivoId);
        $sector = Sector::findOrFail($request->sector_id);

        // Verifica si el sector ya tiene cultivo
        if ($sector->cultivos()->exists()) {
            return response()->json(['message' => 'El sector ya tiene un cultivo plantado'], 400);
        }

        $cultivo->sectores()->attach($sector->id);

        return response()->json(['message' => 'Cultivo plantado correctamente']);
    }

    public function cultivosTipo(Request $request, $tipoId)
    {
        $cultivos = Cultivo::where('tipo_id', $tipoId)->get();
        $cultivosResource = CultivoResource::collection($cultivos);

        return response()->json([
            'status' => 'success',
            'message' => 'Cultivos del tipo especificado obtenidos correctamente.',
            'data' => $cultivosResource
        ], 200);
    }
}


