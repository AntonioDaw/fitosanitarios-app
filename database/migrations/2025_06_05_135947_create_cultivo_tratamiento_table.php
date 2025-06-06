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
        Schema::create('cultivo_tratamiento', function (Blueprint $table) {
            $table->foreignId('tratamiento_id')->constrained()->onDelete('cascade');
            $table->foreignId('cultivo_id')->constrained()->onDelete('cascade');
            $table->primary(['tratamiento_id', 'cultivo_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cultivo_tratamiento');
    }
};
