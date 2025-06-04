<?php

namespace App\Http\Controllers;

use App\Http\Requests\UnidadProductoRequest;
use App\Http\Resources\UnidadProductoResource;
use App\Models\UnidadProducto;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

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

    // Ver detalle de un producto
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

    // Crear producto
    public function store(UnidadProductoRequest $request)
    {

        $unidad_producto = UnidadProducto::create($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Unidad de producto registrada',
            'data' => new UnidadProductoResource($unidad_producto)
        ], 201);
    }

    // Actualizar producto
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

    // Eliminar producto
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
}
