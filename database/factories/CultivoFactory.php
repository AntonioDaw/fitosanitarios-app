<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cultivo>
 */
class CultivoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => $this->faker->word(),
            'imagen' => $this->faker->sentence(),
            'tipo_id' => \App\Models\Tipo::factory(), // Crear un tipo asociado al cultivo
        ];
    }
}
