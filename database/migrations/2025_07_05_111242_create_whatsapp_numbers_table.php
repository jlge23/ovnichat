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
        Schema::create('whatsapp_numbers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas');
            $table->string('numero')->unique();
            $table->string('nombre_opcional')->nullable();
            $table->enum('estado', ['activo', 'inactivo'])->default('activo');
            $table->string('webhook_url')->nullable();
            $table->string('api_token')->nullable();
            $table->timestamps();

            $table->index('estado'); // Para filtrar por estado fácilmente
            $table->index('empresa_id'); // Relación para búsquedas rápidas
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_numbers');
    }
};
