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
        'estado' => true, // valor por defecto al crear el modelo si no se manda
    ];

}
