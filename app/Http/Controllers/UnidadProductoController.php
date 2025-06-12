<?php

namespace App\Http\Controllers;

use App\Http\Requests\UnidadProductoRequest;
use App\Http\Resources\UnidadProductoResource;
use App\Models\UnidadProducto;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UnidadProductoController extends Controller
{
    use ApiResponser;
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $unidad_productosPaginados= UnidadProducto::paginate($perPage);
        $unidad_productosResource = UnidadProductoResource::collection($unidad_productosPaginados);

        return $this->paginatedResponse($unidad_productosResource, $unidad_productosPaginados);
    }


    public function show($id)
    {
        $unidad_producto = UnidadProducto::find($id);

        if (!$unidad_producto) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unidad no encontrada'
            ], 404);
        }

        return response()->json(new UnidadProductoResource($unidad_producto), 200);
    }

    public function store(UnidadProductoRequest $request)
    {

        $unidad_producto = UnidadProducto::create($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Unidad de producto registrada',
            'data' => new UnidadProductoResource($unidad_producto)
        ], 201);
    }
    public function storeLote(Request $request)
    {
        $validated = $request->validate([
            'producto_id' => ['required', 'exists:productos,id'],
            'proveedor_id' => ['required', 'exists:proveedors,id'],
            'cantidad' => ['required', 'integer', 'min:1'],
        ]);

        DB::beginTransaction();

        try {
            for ($i = 0; $i < $validated['cantidad']; $i++) {
                UnidadProducto::create([
                    'producto_id' => $validated['producto_id'],
                    'proveedor_id' => $validated['proveedor_id'],
                    // Agrega más campos si necesitas
                ]);
            }

            DB::commit();

            return response()->json(['message' => 'Unidades registradas correctamente.'], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Error al registrar las unidades.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(UnidadProductoRequest $request, $id)
    {
        $unidad_producto = UnidadProducto::find($id);

        if (!$unidad_producto) {
            return response()->json([
                'status' => 'error',
                'message' => 'Producto no encontrado'
            ], 404);
        }

        $validated = $request->validated();

        $unidad_producto->fill($validated);

        if ($unidad_producto->isClean()) {
            return response()->json([
                'status' => 'info',
                'message' => 'No se realizaron cambios.'
            ], 200);
        }

        $unidad_producto->save();
        return response()->json([
            'status' => 'success',
            'data' => new UnidadProductoResource($unidad_producto)
        ], 200);
    }

    public function destroy($id)
    {
        $unidad_producto = UnidadProducto::find($id);

        if (!$unidad_producto) {
            return response()->json([
                'status' => 'error',
                'message' => 'Producto no encontrado'
            ], 404);
        }

        $unidad_producto->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Producto eliminado correctamente'
        ], 200);
    }

    public function listaUnidadProductos(Request $request)
    {
        $productoId = $request->input('producto_id');

        $query = UnidadProducto::query();

        if ($productoId) {
            $query->where('producto_id', $productoId);
        }

        $unidad_productos = $query->get();

        return UnidadProductoResource::collection($unidad_productos);
    }
}
