<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UnidadProductoResource extends JsonResource
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
            'codigo_interno' => $this->codigo_interno,
            'producto' => [
                'id' => $this->producto->id,
                'nombre' => $this->producto->nombre,
            ],
            'proveedor' => [
                'id' => $this->proveedor->id,
                'nombre' => $this->proveedor->nombre,
            ],
            'fecha_ingreso' => $this->created_at ? $this->created_at->format('Y-m-d') : null,
            'estado' => $this->estado
        ];
    }
}
