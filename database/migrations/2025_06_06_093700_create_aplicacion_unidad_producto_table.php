<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

            Schema::create('aplicacion_unidad_producto', function (Blueprint $table) {
                $table->id();
                $table->foreignId('aplicacion_id')->constrained('aplicacions')->onDelete('cascade');
                $table->foreignId('unidad_producto_id')->constrained('unidad_productos')->onDelete('cascade');
            });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aplicacion_unidad_producto');
    }
};
