<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AplicacionResource extends JsonResource
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
            'user' => [
                'id' => $this->user->id,
                'nombre' => $this->user->name,
            ],
            'tratamiento' => [
                'id' => $this->tratamiento->id,
                'descripcion' => $this->tratamiento->descripcion,
            ],
            'litros' => $this->litros,
            'gasto_por_producto' => $this->gasto_por_producto,
            'sectores' => $this->sectores->map(function ($sector) {
                return [
                    'id' => $sector->id,
                    'numero' => $sector->numero_sector,
                    'litros_aplicados' => $sector->pivot->litros_aplicados,
                ];
            }),
            'unidades_producto' => $this->unidadesProducto->map(function ($unidad) {
                return [
                    'id' => $unidad->id,
                    'producto' => [
                        'id' => $unidad->producto->id,
                        'nombre' => $unidad->producto->nombre,
                    ]
                ];
            }),
            'created_at' => $this->created_at->toDateTimeString(),
            'estado' => $this->estado
        ];
    }
}
