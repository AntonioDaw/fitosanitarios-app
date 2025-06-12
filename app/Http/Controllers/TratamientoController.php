<?php

namespace App\Http\Controllers;

use App\Http\Requests\TratamientoRequest;
use App\Http\Resources\TratamientoResource;
use App\Models\Tratamiento;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class TratamientoController extends Controller
{
    use ApiResponser;
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $tratamientosPaginados= Tratamiento::with(['productos','cultivos', 'tipo'])->paginate($perPage);
        $tratamientosResource = TratamientoResource::collection($tratamientosPaginados);

        return $this->paginatedResponse($tratamientosResource, $tratamientosPaginados);
    }

    public function show($id)
    {
        $tratamiento = Tratamiento::with(['productos','cultivos', 'tipo'])->find($id);

        if (!$tratamiento) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unidad no encontrada'
            ], 404);
        }

        return response()->json(new TratamientoResource($tratamiento), 200);
    }

    public function store(TratamientoRequest $request)
    {
        $tratamiento = Tratamiento::create([
            'tipo_id' => $request->tipo_id,
            'descripcion' => $request->descripcion,
            'estado' => $request->estado ?? 0,
        ]);

        $tratamiento->cultivos()->sync($request->cultivos);

        $productosSyncData = [];
        foreach ($request->productos as $producto) {
            $productosSyncData[$producto['id']] = [
                'cantidad_por_100_litros' => $producto['cantidad_por_100_litros'],
            ];
        }
        $tratamiento->productos()->sync($productosSyncData);

        return response()->json([
            'message' => 'Tratamiento creado correctamente',
            'tratamiento' => $tratamiento->load('cultivos', 'productos'),
        ], 201);
    }

    // Actualizar producto
    public function update(TratamientoRequest $request, $id)
    {

        $tratamiento= Tratamiento::find($id);

        if ($tratamiento->estado !== 0) {
            return response()->json(['error' => 'No se puede modificar un tratamiento que no está pendiente.'], 403);
        }

        if (!$tratamiento) {
           return response()->json([
                'status' => 'error',
                'message' => 'Tratamiento no encontrado'
            ], 404);
        }

        $validated = $request->validated();

        $tratamiento->fill($validated);

        $tratamiento->save();

        $tratamiento->cultivos()->sync($request->cultivos);

        $productosSyncData = [];
        foreach ($request->productos as $producto) {
            $productosSyncData[$producto['id']] = [
                'cantidad_por_100_litros' => $producto['cantidad_por_100_litros'],
            ];
        }
        $tratamiento->productos()->sync($productosSyncData);
        return response()->json([
            'status' => 'success',
            'data' => new TratamientoResource($tratamiento)
        ], 200);
    }

    public function avanzarEstado($id)
    {
        $tratamiento = Tratamiento::findOrFail($id);

        switch ($tratamiento->estado) {
            case 0:
                $tratamiento->estado = 1;
                break;
            case 1:
                $tratamiento->estado = 2;
                break;
            case 2:
                return response()->json([
                    'message' => 'El tratamiento ya está terminado y no puede avanzar más.'
                ], 400);
        }

        $tratamiento->save();

        return response()->json([
            'message' => 'Estado del tratamiento actualizado.',
            'nuevo_estado' => $tratamiento->estado
        ]);
    }

    public function destroy($id)
    {

        $tratamiento = Tratamiento::find($id);

        if ($tratamiento->estado == 1) {
            return response()->json(['error' => 'No se puede borrar un tratamiento activo, quiza olvidaste finalizarlo.'], 403);
        }

        if (!$tratamiento) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tratamiento no encontrado'
            ], 404);
        }

        $tratamiento->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Tratamiento eliminado correctamente'
        ], 200);
    }

    public function tratamientosTipo(Request $request, $tipoId)
    {
        $search = $request->input('search');
        $sortBy = $request->input('sort_by', 'id');
        $sortDir = $request->input('sort_dir', 'asc');

        // Validar sortDir para evitar valores inválidos
        if (!in_array(strtolower($sortDir), ['asc', 'desc'])) {
            $sortDir = 'asc';
        }

        $query = Tratamiento::with(['productos', 'cultivos', 'tipo'])
            ->where('tipo_id', $tipoId);

        if ($search) {
            $query->where('descripcion', 'like', "%{$search}%");
        }

        $query->orderBy($sortBy, $sortDir);

        $tratamientos = $query->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Tratamientos del tipo especificado obtenidos correctamente.',
            'data' => TratamientoResource::collection($tratamientos),
        ], 200);
    }

    public function tratamientosForm(Request $request)
    {
        $tratamientos = Tratamiento::with(['productos', 'cultivos', 'tipo'])
            ->where('estado', 1)->get()
        ;




        return response()->json([
            'status' => 'success',
            'message' => 'Tratamientos del tipo especificado obtenidos correctamente.',
            'data' => TratamientoResource::collection($tratamientos),
        ], 200);
    }


}
