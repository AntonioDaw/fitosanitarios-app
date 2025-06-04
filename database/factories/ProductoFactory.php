<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Producto>
 */
class ProductoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => $this->faker->unique()->word() . ' ' . $this->faker->unique()->randomNumber(3), // ejemplo: Fertilizante 123
            'sustancia_activa' => $this->faker->word(),
            'presentacion' => $this->faker->randomElement(['grano', 'liquido', 'polvo']),
            'uso' => $this->faker->sentence(3),
            'precio' => $this->faker->randomFloat(2, 1, 1000), // entre 1 y 1000 con 2 decimales, o null
            'estado' => $this->faker->boolean(100),
        ];
    }
}
