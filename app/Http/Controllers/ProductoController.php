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
        $search = $request->input('search');
        $sortBy = $request->input('sort_by', 'id');
        $sortDir = $request->input('sort_dir', 'asc');
        $query = Producto::query();
        if ($search) {
            $query->where('nombre', 'like', "%{$search}%");
        }


        if (!in_array(strtolower($sortDir), ['asc', 'desc'])) {
            $sortDir = 'asc';
        }

        $query->orderBy($sortBy, $sortDir);

        $productosPaginados = $query->paginate($perPage);
        $productosResource = ProductoResource::collection($productosPaginados);

        return $this->paginatedResponse($productosResource, $productosPaginados);
    }
    public function listaProductos(Request $request)
    {
        $productos =Producto::all();
        return ProductoResource::collection($productos);
    }

    // Ver detalle de un producto
    public function show($id)
    {
        if (!is_numeric($id)) {
            return response()->json([
                'status' => 'error',
                'message' => 'ID inválido'
            ], 400);
        }

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

        $producto = Producto::create($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Producto registrado',
            'data' => new ProductoResource($producto)
        ], 201);
    }

    // Actualizar producto
    public function update(ProductoRequest $request, $id)
    {
        if (!is_numeric($id)) {
            return response()->json([
                'status' => 'error',
                'message' => 'ID inválido'
            ], 400);
        }
        $producto = Producto::find($id);

        if (!$producto) {
            return response()->json([
                'status' => 'error',
                'message' => 'Producto no encontrado'
            ], 404);
        }

        $validated = $request->validated();

        $producto->fill($validated);

        if ($producto->isClean()) {
            return response()->json([
                'status' => 'info',
                'message' => 'No se realizaron cambios.'
            ], 200);
        }

        $producto->save();
        return response()->json([
            'status' => 'success',
            'data' => new ProductoResource($producto)
        ], 200);
    }

    // Eliminar producto
    public function destroy($id)
    {
        if (!is_numeric($id)) {
            return response()->json([
                'status' => 'error',
                'message' => 'ID inválido'
            ], 400);
        }

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
