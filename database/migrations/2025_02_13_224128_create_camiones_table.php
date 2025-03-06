<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('camiones', function (Blueprint $table) {
            $table->increments('idCamion');
            $table->integer('idProveedor')->unsigned();
            $table->string('potenteCamion', 50);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('idProveedor')->references('idProveedor')->on('proveedores');
        });
    }

    public function down()
    {
        Schema::dropIfExists('camiones');
    }
};