<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductoRequest;
use App\Http\Resources\ProductoResource;
use App\Models\Producto;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    use ApiResponser;
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $productosPaginados = Producto::paginate($perPage);
        $productosResource = ProductoResource::collection($productosPaginados);

        return $this->paginatedResponse($productosResource, $productosPaginados);
    }

    // Ver detalle de un producto
    public function show($id)
    {
        $producto = Producto::find($id);

        if (!$producto) {
            return response()->json([
                'status' => 'error',
                'message' => 'Producto no encontrado'
            ], 404);
        }

        return response()->json(new ProductoResource($producto), 200);
    }

    // Crear producto
    public function store(ProductoRequest $request)
    {
        $validated = $request->validated();

        $producto = Producto::create($validated);

        return response()->json([
            'status' => 'success',
            'data' => new ProductoResource($producto)
        ], 201);
    }

    // Actualizar producto
    public function update(ProductoRequest $request, $id)
    {
        $producto = Producto::find($id);

        if (!$producto) {
            return response()->json([
                'status' => 'error',
                'message' => 'Producto no encontrado'
            ], 404);
        }

        $validated = $request->validated();

        $producto->update($validated);

        return response()->json([
            'status' => 'success',
            'data' => new ProductoResource($producto)
        ], 200);
    }

    // Eliminar producto
    public function destroy($id)
    {
        $producto = Producto::find($id);

        if (!$producto) {
            return response()->json([
                'status' => 'error',
                'message' => 'Producto no encontrado'
            ], 404);
        }

        $producto->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Producto eliminado correctamente'
        ], 200);
    }
}
