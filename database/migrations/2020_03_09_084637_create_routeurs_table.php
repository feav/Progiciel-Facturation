<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRouteursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('routeurs', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->decimal('prix', 16, 8);
            $table->unsignedBigInteger('cree_par');
            $table->unsignedBigInteger('modifie_par')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('routeurs');
    }
}
