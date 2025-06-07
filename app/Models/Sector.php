<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sector extends Model
{
    use HasFactory;

    protected $fillable = ['parcela_id', 'numero_sector'];
    public function cultivos()
    {
        return $this->belongsToMany(Cultivo::class, 'cultivo_sector');
    }

    public function parcela()
    {
        return $this->belongsTo(Parcela::class);
    }

    public function aplicaciones()
    {
        return $this->belongsToMany(Aplicacion::class, 'aplicacion_sector')
            ->withPivot('litros_aplicados')
            ->withTimestamps();
    }

}
