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
            $table->foreignId('proveedores_id')->constrained('proveedores');
            $table->string('codigo')->unique()->nullable();
            $table->integer('codigoBarras')->unique()->nullable();
            $table->string('nombre');
            $table->decimal('costoDlrs', 10, 2);
            $table->decimal('TC',10, 2);
            $table->decimal('costo', 10, 2);
            $table->date('modificacion')->nullable();
            $table->timestamps();
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
