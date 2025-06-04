<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProveedorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'nombre'    => $this->nombre,
            'nif'       => $this->nif,
            'direccion' => $this->direccion,
            'telefono'  => $this->telefono,
            'email'     => $this->email,
            'contacto'  => $this->contacto,
            'estado' => $this->estado ? 'activo' : 'inactivo',
        ];
    }
}
