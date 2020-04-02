<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResultatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resultats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('annonceur_id');
            $table->foreignId('campagne_id');
            $table->unsignedBigInteger('routeur_id');
            $table->foreignId('base_id');
            $table->unsignedBigInteger('volume');
            $table->date('date_envoi');
            $table->time('heure_envoi');
            $table->unsignedBigInteger('resultat', 0);
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
        Schema::dropIfExists('resultats');
    }
}
