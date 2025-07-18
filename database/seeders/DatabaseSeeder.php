<?php

namespace Database\Seeders;

use App\Models\Tipo;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Jesus',
            'email' => 'Jesus@mail.com',
            'role' => 'user',
        ]);
        User::factory()->create([
            'name' => 'Mario',
            'email' => 'Mario@mail.com',
            'role' => 'admin',
        ]);



        $this->call([
            TipoSeeder::class,
            CultivoSeeder::class,
            ParcelaSeeder::class,
            SectorSeeder::class,
            SectorCultivoSeeder::class,
            ProveedorSeeder::class,
            ProductoSeeder::class,
            UnidadProductoSeeder::class,
            TratamientoSeeder::class,
        ]);
    }

}
