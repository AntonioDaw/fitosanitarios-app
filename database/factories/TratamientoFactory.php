<?php

namespace Database\Factories;

use App\Models\Cultivo;
use App\Models\Producto;
use App\Models\Tratamiento;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tratamiento>
 */
class TratamientoFactory extends Factory
{
    protected $model = Tratamiento::class;

    public function definition()
    {
        return [
            'tipo_id' => $this->faker->numberBetween(1, 4), // o tipo de cultivo existente
            'descripcion' => $this->faker->sentence(),
        ];
    }

    // Metodo para asociar cultivos y productos tras crear el tratamiento
    public function configure()
    {
        return $this->afterCreating(function (Tratamiento $tratamiento) {
            $tipoId = $tratamiento->tipo_id;

            // Obtener todos los cultivos de ese tipo
            $cultivosQuery = Cultivo::where('tipo_id', $tipoId);

            $totalCultivos = $cultivosQuery->count();

            $cantidad = rand(1, $totalCultivos);

            $cultivos = $cultivosQuery->inRandomOrder()->take($cantidad)->pluck('id');

            $tratamiento->cultivos()->sync($cultivos);

            $productos = Producto::inRandomOrder()->take(rand(2, 4))->get();

            $productosConCantidad = $productos->mapWithKeys(function ($producto) {
                return [
                    $producto->id => ['cantidad_por_100_litros' => fake()->randomFloat(2, 0.1, 10)]
                ];
            })->toArray();

            $tratamiento->productos()->sync($productosConCantidad);;
        });
    }
}
