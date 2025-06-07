<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aplicacion extends Model
{
    protected $casts = [
        'gasto_por_producto' => 'array',
    ];

    protected $fillable = [
        'user_id',
        'tratamiento_id',
        'litros',
    ];

    public function tratamiento()
    {
        return $this->belongsTo(Tratamiento::class);
    }

    public function sectores()
    {
        return $this->belongsToMany(Sector::class)
            ->withPivot('litros_aplicados');
    }


    public function unidadesProducto()
    {
        return $this->belongsToMany(
            UnidadProducto::class,
            'aplicacion_unidad_producto'
        );
    }

    /**
     * Calcula, para cada producto aplicado, la cantidad usada
     * con base en los litros de esta aplicación y en la “cantidad_por_100_litros”
     * que tiene el producto dentro del tratamiento.
     *
     * Devuelve un array: [ producto_id => cantidad_total_usada, ... ]
     */
    public function calcularGastoPorProducto()
    {
        $resultados = [];

        // 1. Obtenemos los litros totales de esta aplicación
        $litros = $this->litros;

        $productos = $this->tratamiento->productos;

        foreach ($productos as $producto) {


            // Buscamos la “cantidad_por_100_litros” que se definió en el tratamiento
            // entre tratamiento y producto (tabla pivote producto_tratamiento)
            $cantidadPor100 = $this->tratamiento
                ->productos()
                ->where('productos.id', $producto->id)
                ->first()
                ->pivot
                ->cantidad_por_100_litros;

            // Cálculo: (litros / 100) * cantidadPor100
            $cantidadUsada = ($litros / 100) * $cantidadPor100;

            // Acumulamos por cada producto
            if (!isset($resultados[$producto->id])) {
                $resultados[$producto->id] = [
                    'nombre' => $producto->nombre,
                    'cantidad_total' => 0,
                ];
            }

            $resultados[$producto->id]['cantidad_total'] += $cantidadUsada;
        }

        return $resultados;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}

