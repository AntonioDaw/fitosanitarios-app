<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnidadProducto extends Model
{
    /** @use HasFactory<\Database\Factories\UnidadProductoFactory> */
    use HasFactory;

    protected $fillable = [
        'producto_id',
        'proveedor_id',
        'codigo_interno',
        'fecha_ingreso',
        'estado',
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    protected static function booted()
    {
        static::creating(function ($unidad) {
            $fecha = now()->format('Ymd');
            $proveedorId = $unidad->proveedor_id;
            $productoId = $unidad->producto_id;

            // Contar cuántas unidades ya existen para ese proveedor, producto y día
            $count = self::where('proveedor_id', $proveedorId)
                ->where('producto_id', $productoId)
                ->whereDate('created_at', now()->toDateString())
                ->count();

            $correlativo = str_pad($count + 1, 3, '0', STR_PAD_LEFT);
            $unidad->codigo_interno = "UP-{$proveedorId}-{$productoId}-{$fecha}-{$correlativo}";
        });
    }
}
