<?php

namespace App\Http\Controllers;

use App\Http\Resources\SectorResource;
use App\Models\Sector;

class SectorController extends Controller
{
    public function getAll()
    {
        $sectors = SectorResource::collection(Sector::all());

        return response()->json([
            'status' => 'success',
            'data' => $sectors
        ], 200); // OK
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
}
