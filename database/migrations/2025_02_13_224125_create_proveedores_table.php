<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('proveedores', function (Blueprint $table) {
            $table->increments('idProveedor');
            $table->string('nombreProveedor', 100);
            $table->string('apellidoProveedor', 100);
            $table->string('dniProveedor', 20);
            $table->string('razonSocialProveedor', 100);
            $table->string('cuitProveedor', 20);
            $table->string('telefonoProveedor', 20);
            $table->string('mailProveedor', 100);
            $table->string('direccionProveedor', 200);
            $table->string('estadoProveedor', 50);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('proveedores');
    }
};