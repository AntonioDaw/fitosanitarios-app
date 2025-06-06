<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TratamientoResource extends JsonResource
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
            'tipo_id' => $this->tipo_id,
            'descripcion' => $this->descripcion,
            'cultivos' => CultivoTratamientoResource::collection($this->whenLoaded('cultivos')),
            'productos' => ProductoTratamientoResource::collection($this->whenLoaded('productos')),
            'created_at'=> $this->created_at->toDateTimeString(),
            'estado' => $this->estado
        ];
    }
}
