<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reclamos_rto', function (Blueprint $table) {
            $table->increments('idReclamosRto');
            $table->integer('idRto')->unsigned();
            $table->text('descripcionReclamoRto');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('reclamos_rto', function (Blueprint $table) {
            $table->foreign('idRto')->references('idRto')->on('rto');
        });
    }

    public function down()
    {
        Schema::dropIfExists('reclamos_rto');
    }
};