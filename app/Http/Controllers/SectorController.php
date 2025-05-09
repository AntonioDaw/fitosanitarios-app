<?php

namespace App\Http\Controllers;

use App\Http\Requests\SectorRequest;
use App\Http\Resources\SectorResource;
use App\Models\Sector;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;


class SectorController extends Controller
{
    use ApiResponser;
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $sectoresPaginados = Sector::paginate($perPage);
        $sectoresResource = SectorResource::collection($sectoresPaginados);

        return $this->paginatedResponse($sectoresResource, $sectoresPaginados);
    }

    public function show($id)
    {
        $sector = Sector::find($id);

        if (!$sector) {
            return response()->json([
                'status' => 'error',
                'message' => 'Sector no encontrado'
            ], 404); // Not Found
        }

        return response()->json([
            'status' => 'success',
            'data' => new SectorResource($sector)
        ], 200); // OK
    }

    public function store(SectorRequest $request)
    {
        $sector = Sector::create($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Sector creado correctamente.',
            'data' => new SectorResource($sector)
        ], 201);
    }

    public function update(SectorRequest $request, Sector $sector)
    {
        $validated = $request->validated();
        $sector->fill($validated);
        if ($sector->isClean()) {
            return response()->json([
                'status' => 'info',
                'message' => 'No hay cambios que actualizar.'
            ], 200);
        }

        $sector->update($validated);
        //cargo la relacion parcela ya que despues del update no se recarga automaticamente.
        $sector->load('parcela');
        return response()->json([
            'status' => 'success',
            'message' => 'Sector actualizado correctamente.',
            'data' => new SectorResource($sector)
        ]);
    }

    public function delete(Sector $sector)
    {
        $sector->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Sector eliminado correctamente.'
        ]);
    }

    public function sectoresCultivos($tipoId)
    {
        $sectores = Sector::whereHas('cultivos.tipo', function ($query) use ($tipoId) {
            $query->where('id', $tipoId);
        })->with('cultivos.tipo')->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Sectores con cultivos del tipo especificado obtenidos correctamente.',
            'data' => SectorResource::collection($sectores)
        ]);
    }

    public function sectoresSinCultivos()
    {
        $sectores = Sector::doesntHave('cultivos')->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Sectores sin cultivos obtenidos correctamente.',
            'data' => SectorResource::collection($sectores)
        ]);
    }



}
