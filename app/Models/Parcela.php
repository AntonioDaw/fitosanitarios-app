<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Parcela extends Model
{
    use HasFactory;
    protected $fillable = ['nombre', 'numero_parcela', 'area'];
    public function sectors()
    {
        return $this->hasMany(Sector::class);
    }
}
