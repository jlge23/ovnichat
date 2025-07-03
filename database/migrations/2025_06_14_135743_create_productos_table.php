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
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->integer('gtin')->unique()->nullable();
            $table->string('sku')->unique()->nullable();
            $table->string('nombre');
            $table->string('descripcion')->nullable();
            $table->string('unidad_medida', 20);
            $table->decimal('precio_detal', 10, 2);
            $table->decimal('precio_embalaje', 10, 2)->nullable();
            $table->decimal('costo_detal', 10, 2)->nullable();
            $table->integer('stock_actual')->default(0);
            $table->unsignedBigInteger('marca_id')->nullable()->default(0);
            $table->integer('unidades_por_embalaje')->nullable();
            $table->unsignedBigInteger('categoria_id')->nullable();
            $table->unsignedBigInteger('proveedor_id')->nullable();
            $table->unsignedBigInteger('embalaje_id')->nullable();
            $table->longtext('image')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->foreign('categoria_id')->references('id')->on('categorias')->onDelete('set null');
            $table->foreign('proveedor_id')->references('id')->on('proveedores')->onDelete('set null');
            $table->foreign('embalaje_id')->references('id')->on('embalajes')->onDelete('set null');
            $table->foreign('marca_id')->references('id')->on('marcas')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
