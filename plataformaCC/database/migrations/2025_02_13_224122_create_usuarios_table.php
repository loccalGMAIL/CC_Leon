<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->increments('idUsuario');
            $table->string('nombreUsuario', 100);
            $table->string('apellidoUsuario', 100);
            $table->string('dniUsuario', 20)->unique();
            $table->string('mailUsuario', 100)->unique();
            $table->string('usser', 50)->unique();
            $table->string('pass', 100);
            $table->enum('perfilUsuario', ['admin', 'usuario', 'supervisor']);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('usuarios');
    }
};