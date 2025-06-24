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
        Schema::create('mensajes', function (Blueprint $table) {
            $table->id();
            $table->text('mensaje');
            $table->string('tipo_mensaje')->default('texto'); // texto, imagen, audio, etc.
            $table->json('metadatos')->nullable(); // información adicional
            $table->timestamp('inicio_conversacion')->nullable(); // si es el inicio de sesión
            $table->boolean('leido')->default(false);
            $table->foreignId('canale_id')->nullable()->constrained('canales')->nullOnDelete(); // si usas múltiples páginas/números
            $table->foreignId('lead_id')->nullable()->constrained('leads')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mensajes');
    }
};
