<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rto_teoricos', function (Blueprint $table) {
            $table->increments('idRtoTeorico');
            $table->integer('idRto')->unsigned();
            $table->integer('idElementoRto')->unsigned();
            $table->decimal('valorPesosRtoTeorico', 10, 2)->nullable();
            $table->decimal('valorDolaresRtoTeorico', 10, 2)->nullable();
            $table->decimal('subTotalRtoTeorico', 10, 2);
            $table->timestamps();
            $table->softDeletes();
        });

        // Aseguramos que las tablas referenciadas existan antes de crear las foreign keys
        if (Schema::hasTable('rto') && Schema::hasTable('elementos_rto')) {
            Schema::table('rto_teoricos', function (Blueprint $table) {
                $table->foreign('idRto')->references('idRto')->on('rto');
                $table->foreign('idElementoRto')->references('idElementoRto')->on('elementos_rto');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('rto_teoricos');
    }
};