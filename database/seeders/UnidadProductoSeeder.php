<?php

namespace Database\Seeders;

use App\Models\UnidadProducto;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UnidadProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UnidadProducto::factory()->count(100)->create();
    }
}
