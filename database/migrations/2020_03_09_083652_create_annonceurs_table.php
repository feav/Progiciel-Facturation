<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnnonceursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('annonceurs', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('url');
            $table->string('adresse_facturation');
            $table->string('email_comptabilite');
            $table->string('email_direction');
            $table->string('email_production');
            $table->string('delai_paiement');
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
        Schema::dropIfExists('annonceurs');
    }
}
