<?php

namespace App\Http\Controllers;

use App\Http\Requests\AplicacionRequest;
use App\Http\Resources\AplicacionResource;
use App\Models\Tratamiento;
use App\Models\UnidadProducto;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;



use App\Models\Aplicacion;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;


class AplicacionController extends Controller
{
    use ApiResponser;

    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $aplicacionesPaginados = Aplicacion::paginate($perPage);
        $aplicacionesResource = AplicacionResource::collection($aplicacionesPaginados);

        return $this->paginatedResponse($aplicacionesResource, $aplicacionesPaginados);
    }

    public function show($id)
    {
        $aplicacion = Aplicacion::find($id);

        if (!$aplicacion) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unidad no encontrada'
            ], 404);
        }

        return response()->json(new AplicacionResource($aplicacion), 200);
    }
    public function store(AplicacionRequest $request)
    {

        $data = $request->validated();
        $tratamiento = Tratamiento::with('productos')->findOrFail($data['tratamiento_id']);
        $productosTratamientoIds = $tratamiento->productos->pluck('id')->sort()->values();

        $unidadIds = collect($data['unidad_productos'])->pluck('id');
        $unidadProductos = UnidadProducto::with('producto')
            ->whereIn('id', $unidadIds)
            ->get();

        $productosDesdeUnidades = $unidadProductos->pluck('producto.id')->unique()->sort()->values();


        if (!$productosTratamientoIds->diff($productosDesdeUnidades)->isEmpty()) {
            return response()->json([
                'message' => 'Debe incluir al menos una unidad de cada producto del tratamiento.',
                'respuesta 1' => $data
            ], 422);
        }


        $aplicacion = Aplicacion::create([
            'user_id' => $data['user_id'],
            'tratamiento_id' => $data['tratamiento_id'],
            'litros'         => $data['litros'],
        ]);
        $gasto = $aplicacion->calcularGastoPorProducto();

        $gastoJson = collect($gasto)->map(function ($item, $productoId) {
            return [
                'producto_id' => (int) $productoId,
                'nombre' => $item['nombre'],
                'cantidad' => round($item['cantidad_total'], 2),
            ];
        })->values();

        $aplicacion->gasto_por_producto = $gastoJson;
        $aplicacion->save();

        $sectoresConDatos = collect($data['sectors'])->mapWithKeys(function ($item) {
            return [
                $item['id'] => ['litros_aplicados' => $item['litros_aplicados']]
            ];
        })->toArray();

        $aplicacion->sectores()->sync($sectoresConDatos);

        $aplicacion->unidadesProducto()->sync($unidadIds);
        foreach ($data['unidad_productos'] as $item) {
            $unidad = UnidadProducto::findOrFail($item['id']);
            if($unidad->estado<$item['estado']){
                $unidad->estado = $item['estado'];
            }

            $unidad->save();
        }
        return response()->json([
            'message'    => 'Aplicación registrada correctamente.',
            'aplicacion' => new  AplicacionResource($aplicacion),
        ], 201);
    }

    /**
     * Devuelve el desglose de gasto por producto para esta aplicación.
     */
    public function gastoPorProducto($id): JsonResponse
    {
        $aplicacion = Aplicacion::with('unidadProducto.productos', 'tratamiento.productos')
            ->findOrFail($id);

        $gasto = $aplicacion->calcularGastoPorProducto();

        return response()->json([
            'aplicacion_id' => $aplicacion->id,
            'litros'        => $aplicacion->litros,
            'gasto_por_producto' => $gasto,
        ]);
    }
    public function aprobar($id)
    {
        $aplicacion = Aplicacion::findOrFail($id);
        $aplicacion->estado = 'validada';
        $aplicacion->save();

        return response()->json(['success' => true]);
    }

    public function rechazar($id)
    {
        $aplicacion = Aplicacion::findOrFail($id);
        $aplicacion->estado = 'rechazada';
        $aplicacion->save();

        return response()->json(['success' => true]);
    }

}

