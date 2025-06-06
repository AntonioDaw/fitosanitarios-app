<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cultivo extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'tipo_id'];
    public function tipo()
    {
        return $this->belongsTo(Tipo::class);
    }

    public function sectores()
    {
        return $this->belongsToMany(Sector::class, 'cultivo_sector');
    }

    public function tratamientos()
    {
        return $this->belongsToMany(Tratamiento::class);
    }
}
