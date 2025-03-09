<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rto_teoricos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rto_id')->constrained('rto');
            $table->foreignId('elementoRto_id')->constrained('elementos_rto');
            $table->decimal('valorPesosRtoTeorico', 10, 2)->nullable();
            $table->decimal('valorDolaresRtoTeorico', 10, 2)->nullable();
            $table->decimal('TC_RtoTeorico', 10, 2)->nullable();
            $table->decimal('subTotalRtoTeorico', 10, 2);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rto_teoricos');
    }
};