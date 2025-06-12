<?php

namespace App\Http\Resources;

use App\Models\Sector;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ParcelaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'numero_parcela' => $this->numero_parcela,
            'area' => $this->area,
            'n_sectores' => $this->sectors->count(),
            'sectors' => $this->sectors->map(function ($sector) {
                return [
                    'id' => $sector->id,
                    'numero_sector' => $sector->numero_sector,
                    'esta_plantado' => $sector->cultivos->isNotEmpty(),
                ];
            }),
        ];
    }


}
