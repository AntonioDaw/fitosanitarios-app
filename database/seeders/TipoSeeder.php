<?php

namespace Database\Seeders;


use App\Models\Tipo;
use Illuminate\Database\Seeder;

class TipoSeeder extends Seeder
    /**
     * Run the database seeds.
     *
     */

{
    public function run():void
    {
        $tipos = [
            ['nombre' => 'Fresas', 'icono' => 'fresa.png'],
            ['nombre' => 'Arándanos', 'icono' => 'arandano.png'],
            ['nombre' => 'Frambuesas', 'icono' => 'frambuesa.png'],
            ['nombre' => 'Moras', 'icono' => 'mora.png'],
        ];

        foreach ($tipos as $tipo) {
            Tipo::create($tipo);
        }
        $this->command->info('Tipos creados correctamente.');
    }
}
