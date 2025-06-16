<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $rolesMap = [
            'admin' => 'Administrador',
            'user' => 'Trabajador',
        ];
        return [
            'id' => $this->id,
            'name' => $this->name,
            'role' => $rolesMap[$this->role] ?? $this->role,
            'email'=> $this->email,
            'password'=> $this->password
        ];
    }
}
