<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('embeddings', function (Blueprint $table) {
            $table->id();
            $table->text('content')->unique();            // El texto que convertiste en embedding
            $table->json('embedding');                // El array de 768 floats
            $table->foreignId('intent_id')->nullable()->constrained();
            $table->timestamps();                     // created_at y updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('embeddings');
    }
};
