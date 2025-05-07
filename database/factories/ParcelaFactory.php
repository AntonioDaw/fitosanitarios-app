<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Parcela>
 */
class ParcelaFactory extends Factory
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
            'numero_parcela' => $this->faker->numberBetween(1, 100),
            'area' => $this->faker->randomFloat(2, 0.5, 10.0), // entre 0.5 y 10 ha
        ];
    }
}
