<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rto', function (Blueprint $table) {
            $table->increments('idRto');
            $table->integer('idProveedor')->unsigned();
            $table->integer('idCamion')->unsigned();
            $table->string('nroFacturaRto', 50);
            $table->date('fechaIngresoRto');
            $table->decimal('TC_RtoTeorico', 10, 2);
            $table->decimal('TC_RtoFinal', 10, 2);
            $table->decimal('totalTempRto', 10, 2);
            $table->decimal('totalFinalRto', 10, 2);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('rto', function (Blueprint $table) {
            $table->foreign('idProveedor')->references('idProveedor')->on('proveedores');
            $table->foreign('idCamion')->references('idCamion')->on('camiones');
        });
    }

    public function down()
    {
        Schema::dropIfExists('rto');
    }
};