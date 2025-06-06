<?php

namespace Database\Seeders;

use App\Models\Cultivo;
use App\Models\Tipo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CultivoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Obtener todos los tipos ya existentes
        $tipos = Tipo::all();

        // Asegurarnos de que hay tipos en la base de datos
        if ($tipos->isEmpty()) {
            $this->command->info('No hay tipos disponibles en la base de datos.');
            return;
        }

        // Recorrer cada tipo y asociarle 3 cultivos
        foreach ($tipos as $tipo) {
            // Crear 3 cultivos para cada tipo
            Cultivo::factory(6)->create([
                'tipo_id' => $tipo->id,  // Asociar cada cultivo con el tipo actual
            ]);
        }

        $this->command->info('Cultivos asociados correctamente a los tipos.');
    }
}
