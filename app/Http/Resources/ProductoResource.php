<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductoResource extends JsonResource
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
            'sustancia_activa' => $this->sustancia_activa,
            'presentacion' => $this->presentacion,
            'uso' => $this->uso,
            'precio' => $this->precio,
            'estado' => $this->estado,
            'cantidad_por_100_litros' => $this->pivot ? $this->pivot->cantidad_por_100_litros : null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

        ];
    }
}
