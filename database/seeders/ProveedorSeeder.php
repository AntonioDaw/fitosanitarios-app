<?php

namespace Database\Seeders;

use App\Models\Proveedor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProveedorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Proveedor::insert([
            [
                'nombre'    => 'Proveedor Uno',
                'nif'       => '123456789',
                'direccion' => 'Calle Falsa 123',
                'telefono'  => '555123456',
                'email'     => 'uno@proveedor.com',
                'contacto'  => 'Juan Pérez',
                'estado'    => 1,
            ],
            [
                'nombre'    => 'Proveedor Dos',
                'nif'       => '987654321',
                'direccion' => 'Av. Siempre Viva 742',
                'telefono'  => '555987654',
                'email'     => 'dos@proveedor.com',
                'contacto'  => 'María García',
                'estado'    => 1,
            ]
        ]);
    }
}
