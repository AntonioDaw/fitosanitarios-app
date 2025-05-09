<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tipo extends Model
{

    use HasFactory;

    // Definir los campos que se pueden asignar masivamente
    protected $fillable = ['nombre', 'icono'];

    public function getIconoUrlAttribute()
    {
        return config('app.url') . '/icons/' . $this->icono;
    }
    public function cultivos()
    {
        return $this->hasMany(Cultivo::class);
    }
}
