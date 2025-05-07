<?php

namespace Database\Factories;

use App\Models\Parcela;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sector>
 */
class SectorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // Asocia con una parcela existente o crea una nueva
            'parcela_id' => Parcela::inRandomOrder()->first()?->id ?? Parcela::factory(),
            'numero_sector' => $this->faker->numberBetween(1, 4)
        ];
    }
}
