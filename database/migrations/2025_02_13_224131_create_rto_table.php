<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proveedores_id')->constrained('proveedores');
            $table->foreignId('camiones_id')->constrained('camiones');
            $table->string('nroFacturaRto', 50);
            $table->date('fechaIngresoRto');
            $table->decimal('TC_RtoTeorico', 10, 2);
            $table->decimal('TC_RtoFinal', 10, 2);
            $table->decimal('totalTempRto', 10, 2);
            $table->decimal('totalFinalRto', 10, 2);
            $table->timestamps();
            $table->softDeletes();
        });

   }

    public function down()
    {
        Schema::dropIfExists('rto');
    }
};