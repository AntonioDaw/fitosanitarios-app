<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    /** @use HasFactory<\Database\Factories\ProductoFactory> */
    use HasFactory;

    protected $fillable = [
        'nombre',
        'sustancia_activa',
        'presentacion',
        'uso',
        'precio',
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

    public function tratamientos()
    {
        return $this->belongsToMany(Tratamiento::class)
            ->withPivot('cantidad_por_100_litros')
            ->withTimestamps();
    }
}
