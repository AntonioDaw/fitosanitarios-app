<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tratamiento extends Model
{
    /** @use HasFactory<\Database\Factories\TratamientoFactory> */
    use HasFactory;
    protected $fillable = [
        'tipo_id',
        'descripcion',
        'estado',
    ];
    public function cultivos()
    {
        return $this->belongsToMany(Cultivo::class);
    }
    public function productos()
    {
        return $this->belongsToMany(Producto::class)
            ->withPivot('cantidad_por_100_litros')
            ->withTimestamps();
    }

    public function tipo()
    {
        return $this->belongsTo(Tipo::class, 'tipo_id');
    }
}
