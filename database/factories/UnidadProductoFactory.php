<?php

namespace Database\Factories;

use App\Models\Producto;
use App\Models\Proveedor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UnidadProducto>
 */
class UnidadProductoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'producto_id' => Producto::inRandomOrder()->first()->id ?? Producto::factory(),
            'proveedor_id' => Proveedor::inRandomOrder()->first()->id ?? Proveedor::factory(),
            'estado' => 0,
        ];
    }
}
