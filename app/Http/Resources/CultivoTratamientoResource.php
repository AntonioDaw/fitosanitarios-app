<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CultivoTratamientoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'sectores' => $this->sectores->map(function ($sector) {
                return [
                    'id' => $sector->id,
                    'numero' => $sector->numero_sector,
                    // Si hay campos extra en la tabla intermedia:
                    // 'otro_campo' => $sector->pivot->otro_campo,
                ];
            }),
        ];
    }
}
