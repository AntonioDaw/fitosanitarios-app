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
        Schema::create('cultivo_sector', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cultivo_id')->constrained()->onDelete('cascade');
            $table->foreignId('sector_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['cultivo_id', 'sector_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cultivo_sector');
    }
};
