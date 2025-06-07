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

            Schema::create('aplicacion_sector', function (Blueprint $table) {
                $table->id();
                $table->foreignId('aplicacion_id')->constrained()->onDelete('cascade');
                $table->foreignId('sector_id')->constrained()->onDelete('cascade');
                $table->decimal('litros_aplicados', 8, 2);
                $table->unique(['aplicacion_id', 'sector_id']);
            });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aplicacion_sector');
    }
};
