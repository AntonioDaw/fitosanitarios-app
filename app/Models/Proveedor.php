<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{

    use HasFactory;

    protected $fillable = [
        'nombre',
        'nif',
        'direccion',
        'telefono',
        'email',
        'contacto',
        'estado',
    ];

    protected $casts = [
        'estado' => 'boolean',
    ];

    protected $attributes = [
        'estado' => true,
    ];

    public function unidades()
    {
        return $this->hasMany(UnidadProducto::class);
    }
}
