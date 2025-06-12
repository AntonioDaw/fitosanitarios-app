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
            $n = rand(2, 4); // número de sectores a crear

            for ($i = 1; $i <= $n; $i++) {
                Sector::factory()->create([
                    'parcela_id' => $parcela->id,
                    'numero_sector' => $i,
                ]);
            }
        });
    }

}
