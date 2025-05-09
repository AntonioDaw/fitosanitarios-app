<?php

namespace App\Http\Controllers;

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
        $cultivo = Proveedor::create($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Proveedor creado correctamente.',
            'data' => new ProveedorResource($cultivo)
        ], 201);
    }



}
