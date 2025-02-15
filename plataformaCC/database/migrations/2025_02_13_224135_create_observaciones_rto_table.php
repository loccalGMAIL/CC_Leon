<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('observaciones_rto', function (Blueprint $table) {
            $table->increments('idObservacionesRto');
            $table->integer('idRto')->unsigned();
            $table->text('descripcionObservacionesRto');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('observaciones_rto', function (Blueprint $table) {
            $table->foreign('idRto')->references('idRto')->on('rto');
        });
    }

    public function down()
    {
        Schema::dropIfExists('observaciones_rto');
    }
};