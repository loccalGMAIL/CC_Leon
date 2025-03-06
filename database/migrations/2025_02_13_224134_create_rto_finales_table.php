<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rto_finales', function (Blueprint $table) {
            $table->increments('idRtoFinal');
            $table->integer('idRtoTeorico')->unsigned();
            $table->decimal('subTotalRtoFinal', 10, 2);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('rto_finales', function (Blueprint $table) {
            $table->foreign('idRtoTeorico')->references('idRtoTeorico')->on('rto_teoricos');
        });
    }

    public function down()
    {
        Schema::dropIfExists('rto_finales');
    }
};