<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SectorResource extends JsonResource
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
            'numero_sector' => $this->numero_sector,
            'numero_parcela' => $this->parcela->numero_parcela,
            'parcela_nombre' => $this->parcela->nombre,

        ];
    }
}
