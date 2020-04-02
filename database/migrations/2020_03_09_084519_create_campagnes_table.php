<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampagnesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campagnes', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('type_remuneration');
            $table->decimal('remuneration', 16, 8);
            $table->foreignId('annonceur_id');
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
        Schema::dropIfExists('campagnes');
    }
}
