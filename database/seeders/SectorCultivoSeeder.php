<?php

namespace Database\Seeders;

use App\Models\Cultivo;
use App\Models\Parcela;
use App\Models\Sector;
use Illuminate\Database\Seeder;

class SectorCultivoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener todos los cultivos disponibles
        $cultivos = Cultivo::all();

        // Recorrer todos los sectores
        Sector::all()->each(function ($sector) use ($cultivos) {
            // Elegir 0 a 2 cultivos aleatorios (sin repetir)
            $cultivosAsignados = $cultivos->random(rand(0, 2))->pluck('id')->toArray();

            // Sincronizar cultivos con el sector (actualiza tabla pivote)
            $sector->cultivos()->sync($cultivosAsignados);
        });    }
}
