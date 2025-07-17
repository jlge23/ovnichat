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
        Schema::create('canales', function (Blueprint $table) {
            $table->id();
            $table->string('canal'); // Página X, Línea WhatsApp Empresa, etc.
            $table->string('plataforma'); // whatsapp, messenger
            $table->string('token')->nullable(); // token de acceso si aplica
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('canales');
    }
};
