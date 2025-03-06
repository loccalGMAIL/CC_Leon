<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rto_finales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('RtoTeorico_id')->constrained('rto_teoricos');
            $table->decimal('subTotalRtoFinal', 10, 2);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rto_finales');
    }
};