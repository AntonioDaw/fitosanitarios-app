<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProveedorRequest;
use App\Http\Resources\ProveedorResource;
use App\Models\Proveedor;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    use ApiResponser;
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $proveedoresPaginados = Proveedor::paginate($perPage);
        $proveedoresResource = ProveedorResource::collection($proveedoresPaginados);

        return $this->paginatedResponse($proveedoresResource, $proveedoresPaginados);
    }

    public function show($id)
    {
        $proveedor = Proveedor::find($id);

        if (!$proveedor) {
            return response()->json([
                'status' => 'error',
                'message' => 'Proveedor no encontrado',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => new ProveedorResource($proveedor)
        ], 200);
    }

    public function store(ProveedorRequest $request)
    {
        $proveedor = Proveedor::create($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Proveedor creado correctamente.',
            'data' => new ProveedorResource($proveedor)
        ], 201);
    }

    public function update(ProveedorRequest $request, $id)
    {
        // Buscar el proveedor por su ID
        $proveedor = Proveedor::find($id);

        // Si no existe, devolver error 404
        if (!$proveedor) {
            return response()->json([
                'status' => 'error',
                'message' => 'Proveedor no encontrado',
            ], 404);
        }

        // Validar datos con ProveedorRequest

        $validated = $request->validated();

        // Actualizar atributos
        $proveedor->fill($validated);

        if ($proveedor->isClean()) {
            return response()->json([
                'status' => 'info',
                'message' => 'No se realizaron cambios.'
            ], 200);
        }

        // Guardar en la base de datos
        $proveedor->save();

        // Devolver respuesta exitosa
        return response()->json([
            'status' => 'success',
            'message' => 'Proveedor actualizado correctamente.',
            'data' => new ProveedorResource($proveedor)
        ], 200);
    }
    public function delete($id)
    {
        // Buscar proveedor
        $proveedor = Proveedor::find($id);

        if (!$proveedor) {
            return response()->json([
                'status' => 'error',
                'message' => 'Proveedor no encontrado',
            ], 404);
        }

        // Eliminar
        $proveedor->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Proveedor eliminado correctamente.',
        ], 200);
    }

    public function toggleEstado($id)
    {
        $proveedor = Proveedor::find($id);

        if (!$proveedor) {
            return response()->json([
                'status' => 'error',
                'message' => 'Proveedor no encontrado.'
            ], 404);
        }

        // Cambia el estado actual
        $proveedor->estado = !$proveedor->estado;
        $proveedor->save();

        return response()->json([
            'status' => 'success',
            'message' => $proveedor->estado
                ? 'Proveedor activado correctamente.'
                : 'Proveedor desactivado correctamente.',
            'data' => [
                'id' => $proveedor->id,
                'estado' => $proveedor->estado
            ]
        ], 200);
    }


}
