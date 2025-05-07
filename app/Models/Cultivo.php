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

    public function sectors()
    {
        return $this->belongsToMany(Sector::class);
    }

}
