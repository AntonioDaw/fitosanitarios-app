<?php

namespace App\Http\Resources;

use App\Models\Sector;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CultivoResource extends JsonResource
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
            'icono_url' => asset('icons/' . $this->tipo->icono),
            'tipo' => $this->tipo->nombre,
            'tipo_id' => $this->tipo->id,
            'esta_plantado' => $this->sectores()->exists()
        ];
    }


}
