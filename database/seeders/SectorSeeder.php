<?php

namespace Database\Seeders;

use App\Models\Parcela;
use App\Models\Sector;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SectorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Parcela::all()->each(function ($parcela) {
            // Crea entre 2 y 4 sectores para cada parcela existente
            Sector::factory()->count(rand(2, 4))->create([
                'parcela_id' => $parcela->id,
            ]);
        });
    }
}
